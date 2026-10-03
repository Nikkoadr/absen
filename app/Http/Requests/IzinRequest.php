<?php

namespace App\Http\Requests;

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
}
