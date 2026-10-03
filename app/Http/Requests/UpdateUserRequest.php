<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('is_admin') ?? false;
    }

    public function rules(): array
    {
        $id = $this->route('id');

        return [
            'role' => ['required', 'string', Rule::in(['admin', 'karyawan', 'guru', 'siswa'])],
            'nik' => ['nullable', 'string', 'max:16'],
            'nuptk' => ['nullable', 'string', 'max:16'],
            'nbm' => ['nullable', 'string', 'max:20'],
            'nama' => ['required', 'string', 'max:255'],
            'nomor_hp' => ['nullable', 'string', 'max:15'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($id)],
            'jabatan' => ['nullable', 'string', 'max:255'],
            'jam_kerja' => ['nullable', 'date_format:H:i,H:i:s'],
            'jam_pulang' => ['nullable', 'date_format:H:i,H:i:s'],
        ];
    }
}
