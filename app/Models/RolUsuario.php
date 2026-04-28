<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class RolUsuario extends Pivot
{
    protected $table = 'usuario_rol';
}
