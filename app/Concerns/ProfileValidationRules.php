<?php

namespace App\Concerns;

use App\Models\User;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Validation\Rule;

trait ProfileValidationRules
{
    /**
     * Get the validation rules used to validate user profiles.
     *
     * @return array<string, array<int, ValidationRule|array<mixed>|string>>
     */
    protected function profileRules(?int $userId = null): array
    {
        return [
            'cedula'   => $this->cedulaRules($userId),
            'nombre'   => $this->nameRules(),
            'apellido' => $this->nameRules(),
            'email'    => $this->emailRules($userId),
            'telefono' => $this->telefonoRules(),
        ];
    }

    protected function cedulaRules(?int $userId = null): array
    {
        return [
            'required', 'numeric', 'regex:/^[0-9]{6,8}$/', 'unique:usuarios,cedula',
            Rule::unique('usuarios', 'cedula')->when($userId !== null, fn($q) => $q->ignore($userId)),
        ];
    }

    protected function telefonoRules(): array
    {
        return[
            'required', 'string', 'max:9', 'regex:/^(?:09\d|5989\d)\s?\d{3}\s?\d{3}$/',
        ];
    }

    /**
     * Get the validation rules used to validate user names.
     *
     * @return array<int, ValidationRule|array<mixed>|string>
     */
    protected function nameRules(): array
    {
        return ['required', 'string', 'max:255'];
    }

    /**
     * Get the validation rules used to validate user emails.
     *
     * @return array<int, ValidationRule|array<mixed>|string>
     */
    protected function emailRules(?int $userId = null): array
    {
        return [
            'required',
            'string',
            'email',
            'max:255',
            $userId === null
                ? Rule::unique(User::class)
                : Rule::unique(User::class)->ignore($userId),
        ];
    }
}
