<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;

#[Fillable(['email', 'password'])]
#[Hidden(['password', 'two_factor_secret', 'two_factor_recovery_codes', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable, TwoFactorAuthenticatable;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'two_factor_confirmed_at' => 'datetime',
        ];
    }

    protected $table = 'usuarios';

    public function propiedades(): HasMany
    {
        return $this->hasMany(Propiedad::class, 'usuario_id');
    }

    public function inmobiliaria(): HasOne
    {
        return $this->hasOne(Inmobiliaria::class, 'usuario_id');
    }

    public function perfil_persona(): HasOne
    {
        return $this->hasOne(PerfilPersona::class, 'usuario_id');
    }

    public function rol_usuario(): BelongsToMany
    {
        return $this->belongsToMany(Rol::class, 'usuario_rol', 'usuario_id', 'rol_id');
    }

    public function favoritos(): BelongsToMany
    {
        return $this->belongsToMany(Propiedad::class, 'favoritos', 'usuario_id', 'propiedad_id');
    }

    public function imagenes_propiedad(): HasMany
    {
        return $this->hasMany(ImagenPropiedad::class, 'usuario_id');
    }
}
