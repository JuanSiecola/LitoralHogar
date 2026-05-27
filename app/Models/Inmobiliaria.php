<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Inmobiliaria extends Model
{
    protected $table = 'inmobiliaria';

    protected $fillable = ['usuario_id', 'razon_social', 'rut', 'direccion', 'telefono', 'logo_url', 'logo_public_id'];

    public $timestamps = false;

    public function usuario(): BelongsTo
    {
        return $this->belongsTo(User::class, 'usuario_id');
    }
}
