<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Ubicacion extends Model
{
    protected $table = 'ubicacion';

    public $timestamps = false;

    protected $fillable = ['direccion', 'departamento_id', 'localidad_id', 'longitud', 'latitud', 'propiedad_id'];

    protected $casts = [
        'latitud' => 'decimal:8',
        'longitud' => 'decimal:8',
    ];

    public function propiedad(): BelongsTo
    {
        return $this->belongsTo(Propiedad::class, 'propiedad_id');
    }

    public function departamento(): BelongsTo
    {
        return $this->belongsTo(Departamento::class)->withDefault([
            'nombre' => 'Sin departamento',
        ]);
    }

    public function localidad(): BelongsTo
    {
        return $this->belongsTo(Localidad::class)->withDefault([
            'nombre' => 'Sin localidad',
        ]);
    }
}
