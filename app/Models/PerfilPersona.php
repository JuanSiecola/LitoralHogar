<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\User;

class PerfilPersona extends Model
{
    protected $table = 'perfil_persona';

    protected $fillable = ['usuario_id', 'nombre', 'apellido', 'cedula', 'telefono', 'foto_url'];

    public $timestamps = false;

    public function usuario(): BelongsTo
    {
        return $this->belongsTo(User::class, 'usuario_id');
    }
}
