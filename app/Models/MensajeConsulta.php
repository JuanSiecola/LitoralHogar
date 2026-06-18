<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MensajeConsulta extends Model
{
    protected $table = 'mensajes_consulta';

    protected $fillable = ['consulta_id', 'usuario_id', 'mensaje', 'leido'];

    protected $casts = [
        'leido' => 'boolean',
    ];

    public function consulta(): BelongsTo
    {
        return $this->belongsTo(Consulta::class, 'consulta_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'usuario_id');
    }
}