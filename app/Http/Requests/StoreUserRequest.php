<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('is_admin') ?? false;
    }

    public function rules(): array
    {
        return [
            'role' => ['required', 'string', Rule::in(['admin', 'karyawan', 'guru', 'siswa'])],
            'nik' => ['nullable', 'string', 'max:16'],
            'nuptk' => ['nullable', 'string', 'max:16'],
            'nbm' => ['nullable', 'string', 'max:20'],
            'nama' => ['required', 'string', 'max:255'],
            'nomor_hp' => ['nullable', 'string', 'max:15'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'jabatan' => ['nullable', 'string', 'max:255'],
            'jam_kerja' => ['nullable', 'date_format:H:i,H:i:s'],
            'jam_pulang' => ['nullable', 'date_format:H:i,H:i:s'],
        ];
    }
}
