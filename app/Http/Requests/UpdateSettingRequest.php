<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSettingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('is_admin') ?? false;
    }

    public function rules(): array
    {
        return [
            'nama_lokasi' => ['required', 'string', 'max:150'],
            'latitude' => ['required', 'numeric', 'between:-90,90'],
            'longitude' => ['required', 'numeric', 'between:-180,180'],
            'radius' => ['required', 'integer', 'min:1', 'max:100000'],
            'limit_absen' => ['required', 'date_format:H:i,H:i:s'],
            'telegram_bot_token' => ['nullable', 'string', 'max:100'],
            'telegram_aktif' => ['nullable', 'boolean'],
        ];
    }
}
