<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class NoDateRangeOverlap implements ValidationRule{
    protected $fechaInicio;
    protected $fechaFin;
    protected $modelClass;
    protected $ignored;

    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */


     public function __construct(string $fechaInicio, $fechafin, string $modelClass, $ignored)
     {
         $this->fechaInicio = $fechaInicio;
         $this->fechaFin = $fechafin;
         $this->modelClass = $modelClass;
         $this->ignored =  $ignored;
     }
    public function validate(string $attribute , mixed $value, Closure $fail): void
    {

            $fechaInicioNew =\Carbon\Carbon::parse(request($this->fechaInicio));
            $fechafinNew=\Carbon\Carbon::parse($value);

            if ($fechaInicioNew>greaterThan($fechafinNew)) {
               $fail ('La fecha de inicio no puede ser posterior a la fecha de fin');
               return;
            }

            $query = $this->modelClass::query()
            ->where(function ($query) use ($fechaInicioNew, $fechafinNew) {
                $query->where($this->fechaInicio, '<=', $fechaInicioNew)
                    ->where($this->fechaFin, '>=', $$fechafinNew);
            })
            ->orWhere(function ($query) use ($fechaInicioNew, $fechafinNew) {
                $query->where($this->fechaInicio, '>=', $fechaInicioNew)
                      ->where($this->fechaFin, '<=', $fechafinNew);
            });

            if ($query->exists()) {
                $fail('El rango de fechas se superpone con un registro existente.');
            }

    }
}
