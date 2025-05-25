<?php

namespace App\Http\Controllers;

use App\Models\Cita;
use App\Models\Disponibilidad;
use App\Models\Servicio;
use App\Models\User;
use App\Rules\NoDateRangeOverlap;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Google\Service\Gmail\Message;

class CitaController extends Controller
{
    public function index(){
        $citas = Cita::with('disponibilidad')
        ->with('servicio')
        ->with('user')
        ->where('estado','pendiente')
        ->get();
        return response()->json($citas);
    }
    public function indexAcept(){
         $citas = Cita::with('disponibilidad')
        ->with('servicio')
        ->with('user')
        ->where('estado','aceptada')
        ->where('fecha_fin', '>=', now())
        ->get();
        return response()->json($citas);

    }

    public function indexpendiente(){
         $citas = Cita::with('disponibilidad')
        ->with('servicio')
        ->with('user')
        ->where('estado','pendiente')
        ->get();
        return response()->json($citas);

    }

        public function indexpasada(){
         $citas = Cita::with('disponibilidad')
        ->with('servicio')
        ->with('user')
        ->where('estado','aceptada')
        ->where('fecha_fin', '<', now())
        ->get();
        return response()->json($citas);

    }




    public function store(Request $request){
        $request->validate([
            'dolencia' => 'nullable|string',
            'disponibilidad' => 'nullable|array',
            'servicio'=> 'required|exists:servicios,id',


        ]);
        $input = $request->all();

        try{
            $cita = new Cita($input);
            $servicio= Servicio::findOrFail($request->input("servicio"));
            $cita->servicio()->associate($servicio);
            $user= Auth::user();
            $cita->user()->associate($user);
            $cita-> estado='pendiente';
            if($cita->save()){
                $this->disponibilidad($input["disponibilidad"],$cita->id );
            }
            return $cita;



        }catch (\Exception $exception){
            return response()->json(['error' => $exception->getMessage()]);
        }


    }


    public function disponibilidad(array $disponibilidad , $cita_id = null ){
            foreach ($disponibilidad as $disponibilidad) {
                $disponibilidad=new disponibilidad($disponibilidad);
                $disponibilidad->cita_id  = $cita_id;
                $disponibilidad->save();
            }
    }

    public function aceptarCita(Request $request, $id){
        $cita = Cita::findOrFail($id);
        try{
            $request->validate([
                'fecha_inicio' => 'required|date',
                'fecha_fin' => 'required|date|after:fecha_inicio',new NoDateRangeOverlap('fecha_inicio', 'fecha_fin', Cita::class, $cita->id)

            ]); 
            $input = $request->all();
            $cita->fecha_inicio = $input['fecha_inicio'];
            $cita->fecha_fin = $input['fecha_fin'];
            $cita->estado = 'aceptada';
            $cita->save();

            return $cita;
        }catch (\Exception $exception){
            return response()->json(['error' => $exception->getMessage()]);
        }
        
    }
    

    public function eliminarCita($id){
        $cita = Cita::findOrFail($id);
        $cita->fecha_inicio = null;
        $cita->fecha_fin = null;
        $cita->estado = 'pendiente';
        $cita->save();

    }

    public function editarCita(Request $request, $id){
        $cita = Cita::findOrFail($id);
        $request->validate([
            'fecha_inicio' => 'required|date',
            'fecha_fin' => 'required|date|after:fecha_inicio',new NoDateRangeOverlap('fecha_inicio', 'fecha_fin', Cita::class, $cita->id)

        ]);
        $input = $request->all();
        $cita->update($input);
        return response()->json($cita);
    }

}
