<?php

namespace App\Http\Requests\Settings;

use App\Concerns\Inmobiliaria\InmobiliariaValidationRules;
use App\Concerns\Profile\ProfileValidationRules;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProfileUpdateRequest extends FormRequest
{
    use ProfileValidationRules, InmobiliariaValidationRules;

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $user = $this->user();
        $user->loadMissing(['rol_usuario', 'inmobiliaria']);

        $isInmobiliaria = $user->rol_usuario
            ->contains(fn($r) => str_contains(strtolower($r->nombre), 'inmobiliaria'));

        $emailRule = [
            'required', 'string', 'email', 'max:255',
            Rule::unique('usuarios', 'email')->ignore($user->id),
        ];

        if ($isInmobiliaria) {
            return array_merge(
                ['email' => $emailRule],
                $this->inmobiliariaRules($user->inmobiliaria?->id)
            );
        }

        return $this->profileRules($user->id);
    }

    public function messages(): array
    {
        return array_merge($this->profileMessages(), $this->inmobiliariaMessages());
    }
}
