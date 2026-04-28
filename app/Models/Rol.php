<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Model;

class Rol extends Model
{
    protected $table = 'roles';

    public function usuarios(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'usuario_rol', 'rol_id', 'usuario_id');
    }
}
