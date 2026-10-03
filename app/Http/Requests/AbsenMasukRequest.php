<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AbsenMasukRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    public function rules(): array
    {
        return [
            'lokasi' => ['required', 'string', 'regex:/^-?\d+(\.\d+)?,-?\d+(\.\d+)?$/'],
            'foto' => ['required', 'string', 'min:100'],
        ];
    }

    public function messages(): array
    {
        return [
            'lokasi.regex' => 'Format lokasi tidak valid.',
            'foto.min' => 'Foto minimal berisi 100 karakter.',
        ];
    }

    public function koordinat(): array
    {
        return array_map('floatval', explode(',', $this->string('lokasi')->toString()));
    }

    public function fotoBiner(): ?string
    {
        $parts = explode('base64,', (string) $this->input('foto'));

        if (count($parts) !== 2 || $parts[1] === '') {
            return null;
        }

        $decoded = base64_decode($parts[1], true);

        return $decoded === false ? null : $decoded;
    }
}
