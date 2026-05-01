<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DetallePropiedad extends Model
{
    protected $table = 'detalle_propiedad';

    protected $fillable = [
        'nro_habitaciones',
        'nro_banios',
        'nro_garage',
        'superficie_total',
        'pisos',
        'precio',
        'anio_construccion',
        'estado_construccion',
        'deposito',
        'cant_meses_deposito',
        'expensas',
        'acepta_mascotas',
        'propiedad_id',
    ];

    public function propiedad(): BelongsTo
    {
        return $this->belongsTo(Propiedad::class, 'propiedad_id');
    }
}
