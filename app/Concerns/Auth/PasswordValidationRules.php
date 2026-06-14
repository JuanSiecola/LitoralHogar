<?php

namespace App\Concerns\Auth;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Validation\Rules\Password;

trait PasswordValidationRules
{
    /**
     * Get the validation rules used to validate passwords.
     *
     * @return array<int, ValidationRule|array<mixed>|string>
     */
    protected function passwordRules(): array
    {
        return ['required', 'string', Password::min(8)->mixedCase()->symbols(), 'confirmed'];
    }

    /**
     * Get the validation rules used to validate the current password.
     *
     * @return array<int, ValidationRule|array<mixed>|string>
     */
    protected function currentPasswordRules(): array
    {
        return ['required', 'string', Password::min(8)->mixedCase()->symbols(), 'current_password'];
    }

    protected function passwordMessages(): array
    {
        return [
            'password.required' => 'La contraseña es obligatoria.',
            'password.string' => 'La contraseña debe ser una cadena de texto.',
            'password.min' => 'La contraseña debe tener al menos 8 caracteres.',
            'password.mixedCase' => 'La contraseña debe contener al menos una letra mayúscula y una letra minúscula.',
            'password.symbols' => 'La contraseña debe contener al menos un símbolo.',
            'password.confirmed' => 'La confirmación de la contraseña no coincide.',
        ];
    }
}
