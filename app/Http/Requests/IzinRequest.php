<?php

namespace App\Http\Requests;

use App\Models\LeaveRequest;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class IzinRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null && $this->user()->role !== 'admin';
    }

    public function rules(): array
    {
        return [
            'jenis' => ['required', 'string', Rule::in(['izin', 'sakit', 'cuti', 'dinas_luar'])],
            'tanggal_mulai' => ['required', 'date'],
            'tanggal_selesai' => ['nullable', 'date', 'after_or_equal:tanggal_mulai'],
            'keterangan' => ['nullable', 'string', 'max:1000'],
        ];
    }

    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            $mulai = $this->input('tanggal_mulai');

            if (! $mulai || ! $this->user()) {
                return;
            }

            $selesai = $this->input('tanggal_selesai') ?: $mulai;
            $bentrok = LeaveRequest::milikPengguna($this->user()->id)
                ->whereIn('status', ['pending', 'disetujui'])
                ->where('tanggal_mulai', '<=', $selesai)
                ->where(function ($q) use ($mulai) {
                    $q->whereNull('tanggal_selesai')->orWhere('tanggal_selesai', '>=', $mulai);
                })
                ->exists();

            if ($bentrok) {
                $validator->errors()->add('tanggal_mulai', 'Sudah ada pengajuan pada rentang tanggal tersebut.');
            }
        });
    }
}
