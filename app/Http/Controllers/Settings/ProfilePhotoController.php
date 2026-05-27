<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use App\Services\ImageUploadService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Concerns\Profile\ProfilePhotoValidationRules;

class ProfilePhotoController extends Controller
{
    use ProfilePhotoValidationRules;

    public function store(Request $request, ImageUploadService $uploader): RedirectResponse
    {
        $request->validate($this->profilePhotoRules(), $this->profilePhotoMessages());

        $user = $request->user();
        $user->loadMissing(['rol_usuario', 'perfil_persona', 'inmobiliaria']);

        $result = $uploader->upload($request->file('photo'), 'litoral-hogar/profiles');

        $isInmobiliaria = $user->rol_usuario
            ->contains(fn($r) => str_contains(strtolower($r->nombre), 'inmobiliaria'));

        if ($isInmobiliaria) {
            $old = $user->inmobiliaria?->logo_public_id;
            if ($old) $uploader->delete($old);

            $user->inmobiliaria()->updateOrCreate([], [
                'logo_url'       => $result['secure_url'],
                'logo_public_id' => $result['public_id'],
            ]);
        } else {
            $old = $user->perfil_persona?->foto_public_id;
            if ($old) $uploader->delete($old);

            $user->perfil_persona()->updateOrCreate([], [
                'foto_url'       => $result['secure_url'],
                'foto_public_id' => $result['public_id'],
            ]);
        }

        return back();
    }
}
