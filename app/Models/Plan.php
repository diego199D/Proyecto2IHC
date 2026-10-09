<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Plan extends Model
{
    
    protected $table = 'planes';

    
    protected $casts = [
        'fecha_limite' => 'date',
    ];
}
