<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PerfilPersona extends Model
{
    protected $table = 'perfil_persona';

    protected $fillable = ['usuario_id', 'nombre', 'apellido', 'cedula', 'telefono'];

    public $timestamps = false;

    public function usuario(): BelongsTo
    {
        return $this->belongsTo(User::class, 'usuario_id');
    }
}
