<?php

namespace App\Concerns\Profile;

trait ProfilePhotoValidationRules
{
    protected function profilePhotoRules(): array
    {
        return [
            'photo' => ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ];
    }

    protected function profilePhotoMessages(): array
    {
        return [
            'photo.required' => 'La foto de perfil es obligatoria.',
            'photo.image'    => 'El archivo debe ser una imagen.',
            'photo.max'      => 'La foto de perfil no debe superar los 2MB.',
            'photo.mimes'    => 'Solo se aceptan JPG, PNG o WEBP.',
        ];
    }
}
