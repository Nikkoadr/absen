<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FilterTanggalRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    public function rules(): array
    {
        return [
            'hari' => ['nullable', 'integer', 'min:1', 'max:31'],
            'bulan' => ['nullable', 'integer', 'min:1', 'max:12'],
            'tahun' => ['nullable', 'integer', 'min:2000', 'max:2100'],
        ];
    }

    public function hari(): int
    {
        return (int) ($this->input('hari') ?? now('Asia/Jakarta')->day);
    }

    public function bulan(): int
    {
        return (int) ($this->input('bulan') ?? now('Asia/Jakarta')->month);
    }

    public function tahun(): int
    {
        return (int) ($this->input('tahun') ?? now('Asia/Jakarta')->year);
    }
}
