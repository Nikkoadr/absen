<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Carbon;
use Illuminate\Validation\Rule;

class LaporanRentangRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('is_admin') ?? false;
    }

    public function rules(): array
    {
        return [
            'tanggal_awal' => ['required', 'date'],
            'tanggal_akhir' => ['required', 'date', 'after_or_equal:tanggal_awal'],
            'kelompok' => ['required', 'string', Rule::in(['karyawan', 'siswa'])],
            'kelas_id' => ['nullable', 'integer', 'exists:kelas,id'],
        ];
    }

    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            $awal = $this->input('tanggal_awal');
            $akhir = $this->input('tanggal_akhir');

            if ($awal && $akhir && Carbon::parse($awal)->diffInDays(Carbon::parse($akhir)) > 62) {
                $validator->errors()->add('tanggal_akhir', 'Rentang tanggal maksimal 62 hari.');
            }
        });
    }
}
