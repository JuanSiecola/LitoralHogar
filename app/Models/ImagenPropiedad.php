<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ImagenPropiedad extends Model
{
    protected $table = 'imagen_propiedad';

    public $timestamps = false;

    protected $fillable = [
        'url',
        'public_id',
        'orden',
        'es_principal',
        'titulo',
        'descripcion',
        'propiedad_id',
        'usuario_id',
    ];

    public function usuario(): BelongsTo
    {
        return $this->belongsTo(User::class, 'usuario_id');
    }
    public function propiedad(): BelongsTo
    {
        return $this->belongsTo(Propiedad::class, 'propiedad_id');
    }
}
