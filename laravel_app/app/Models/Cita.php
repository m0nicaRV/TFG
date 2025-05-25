<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Servicio;

class Cita extends Model
{
    use HasFactory;

    protected $fillable = [
        'dolencia',
        'estado',
        'users_id',
        'servicio_id',
    ];

    public function Disponibilidad(){
        return $this->hasMany(Disponibilidad::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class, 'users_id'); // 'users_id' es la clave foránea en esta tabla que apunta a 'users'
    }

    public function servicio()
    {
        return $this->belongsTo(Servicio::class, 'servicio_id'); // 'servicio_id' es la clave foránea en esta tabla que apunta a 'servicios'
    }


}
