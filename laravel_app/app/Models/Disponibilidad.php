<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Disponibilidad extends Model
{
    use HasFactory;

    protected $fillable = [
        'inicio',
        'fin',
        'semana',
        'cita_id'
    ];

    public function cita(){
        return $this->belongsTo(Cita::class);
    }
}
