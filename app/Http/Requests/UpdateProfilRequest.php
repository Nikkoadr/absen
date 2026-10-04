<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;

class UpdateProfilRequest extends FormRequest
{
    public function authorize(): bool
    {
        $user = $this->route('user');
        $id = (int) ($user instanceof \App\Models\User ? $user->id : $user);

        return (int) $this->user()->id === $id || Gate::allows('is_admin');
    }

    public function rules(): array
    {
        $user = $this->route('user');
        $id = $user instanceof \App\Models\User ? $user->id : $user;

        return [
            'nik' => ['nullable', 'string', 'max:16'],
            'nuptk' => ['nullable', 'string', 'max:16'],
            'nbm' => ['nullable', 'string', 'max:20'],
            'nama' => ['required', 'string', 'max:255'],
            'nomor_hp' => ['nullable', 'string', 'max:15'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($id)],
        ];
    }
}
