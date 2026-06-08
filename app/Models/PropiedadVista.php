<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PropiedadVista extends Model
{
    protected $table = 'propiedad_vistas';

    protected $fillable = ['propiedad_id', 'usuario_id', 'session_id', 'ip', 'fecha'];

    public function propiedad(): BelongsTo
    {
        return $this->belongsTo(Propiedad::class, 'propiedad_id');
    }
}