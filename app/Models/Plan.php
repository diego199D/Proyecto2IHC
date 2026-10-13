<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Plan extends Model
{
    use HasFactory;

    protected $table = 'planes';

    protected $fillable = ['nombre', 'organizador', 'fecha_limite', 'estado', 'user_id'];

    protected $casts = [
        'fecha_limite' => 'date',
    ];

    // Scope para búsqueda y filtros combinables
    public function scopeFiltrar($query, ?string $buscar, ?string $estado, ?string $fecha)
    {
        if ($buscar) {
            $query->where('nombre', 'like', "%{$buscar}%");
        }

        if ($estado) {
            $query->where('estado', $estado);
        }

        if ($fecha === '7dias') {
            $query->whereBetween('fecha_limite', [now()->startOfDay(), now()->addDays(7)->endOfDay()]);
        } elseif ($fecha === 'vencidos') {
            $query->where('fecha_limite', '<', now()->startOfDay());
        }

        return $query;
    }
}
