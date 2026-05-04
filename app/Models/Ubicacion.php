<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Ubicacion extends Model
{
    protected $table = 'ubicacion';

    public $timestamps = false;

    protected $fillable = ['direccion', 'ciudad', 'departamento', 'longitud', 'latitud', 'propiedad_id'];

    public function propiedad(): BelongsTo
    {
        return $this->belongsTo(Propiedad::class, 'propiedad_id');
    }
}
