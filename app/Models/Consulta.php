<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Consulta extends Model
{
    protected $table = 'consultas';

    protected $fillable = ['usuario_id', 'propiedad_id', 'mensaje', 'respuesta', 'estado'];

    public function propiedad(): BelongsTo
    {
        return $this->belongsTo(Propiedad::class, 'propiedad_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'usuario_id');
    }
    
    public function mensajes(): HasMany
    {
        return $this->hasMany(MensajeConsulta::class, 'consulta_id')->orderBy('created_at');
    }
 
    public function ultimoMensaje(): ?MensajeConsulta
    {
        return $this->mensajes()->latest()->first();
    }
 
    
    public function noLeidosPara(int $usuarioId): int
    {
        return $this->mensajes()
            ->where('usuario_id', '!=', $usuarioId)
            ->where('leido', false)
            ->count();
    }
}