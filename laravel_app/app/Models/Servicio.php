<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Cita;

class Servicio extends Model
{
    use HasFactory;

    protected $fillable = [
        'titulo',
        'tiempo',
        'descripcion',
        'foto'
    ];

    public function citas()
    {
        return $this->hasMany(Cita::class, 'servicio_id'); // 'servicio_id' es la clave foránea en la tabla 'citas' que apunta a 'servicios'
    }

}

