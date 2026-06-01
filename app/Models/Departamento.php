<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Departamento extends Model
{
    protected $table = 'departamento';

    public $timestamps = false;

    protected $fillable = ['nombre'];

    public function localidades(): HasMany
    {
        return $this->hasMany(Localidad::class);
    }

    public function ubicaciones(): HasMany
    {
        return $this->hasMany(Ubicacion::class);
    }
}
