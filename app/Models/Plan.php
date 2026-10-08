<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Plan extends Model
{
    // Laravel buscaría la tabla "plans" (plural en inglés), así que le decimos el nombre
    protected $table = 'planes';

    // Para poder usar ->format('d/m/Y') en la vista
    protected $casts = [
        'fecha_limite' => 'date',
    ];
}
