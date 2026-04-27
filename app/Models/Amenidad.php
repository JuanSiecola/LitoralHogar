<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Amenidad extends Model
{
    protected $table = "amenidad";

    public function propiedades(): BelongsToMany 
    {
        return $this->belongsToMany(Propiedad::class, 'amenidad_propiedad', 'amenidad_id', 'propiedad_id');
    }
}
