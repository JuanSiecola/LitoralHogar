<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Propiedad extends Model
{
    //esto es para especificar el nombre de la tabla exacto en la bd
    protected $table = 'propiedad';

    protected $fillable = ['titulo', 'estado_propiedad', 'tipo_propiedad', 'tipo_operacion', 'calificacion', 'usuario_id'];

    public $timestamps = false;

    public function usuario(): BelongsTo
    {
        return $this->belongsTo(User::class, 'usuario_id');
    }

    public function amenidades(): BelongsToMany
    {
        return $this->belongsToMany(Amenidad::class, 'amenidad_propiedad', 'propiedad_id', 'amenidad_id');
    }

    public function detalle_propiedad(): HasOne
    {
        return $this->hasOne(DetallePropiedad::class, 'propiedad_id');
    }

    public function ubicacion(): HasOne
    {
        return $this->hasOne(Ubicacion::class, 'propiedad_id');
    }

    public function imagenes(): HasMany
    {
        return $this->hasMany(ImagenPropiedad::class, 'propiedad_id');
    }

    public function favoritos(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'favorito', 'propiedad_id', 'usuario_id');
    }
}
