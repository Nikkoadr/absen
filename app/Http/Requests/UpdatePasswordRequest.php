<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePasswordRequest extends FormRequest
{
    public function authorize(): bool
    {
        $peminta = $this->user();

        if ($peminta === null) {
            return false;
        }

        $target = $this->route('user');
        $targetId = $target instanceof \App\Models\User ? $target->id : (int) $target;

        return (int) $peminta->id === (int) $targetId || $peminta->can('is_admin');
    }

    public function rules(): array
    {
        return [
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ];
    }
}
