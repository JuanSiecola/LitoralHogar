<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DetalleInmobiliaria extends Model
{
    protected $table = 'detalle_inmobiliaria';

    public function usuarios(): BelongsTo
    {
        return $this->belongsTo(User::class, 'usuario_id');
    }
}
