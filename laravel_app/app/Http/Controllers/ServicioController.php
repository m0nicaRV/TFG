<?php

namespace App\Http\Controllers;

use http\Env\Response;
use Illuminate\Http\Request;
use App\Models\Servicio;

class ServicioController extends Controller
{
    public function __construct(){
        $this->middleware('auth:api', ['except' => ['index', 'show']]);
    }
    public function index()
    {
        try{
            $servicios= Servicio::all();
            return $servicios;
        }catch (\Exception $exception){
            return response()->json(['error'=>$exception->getMessage()]);
        }

    }


    public function store(Request $request)
     {

         try{
             $request->validate([
                 'titulo' => 'nullable|string',
                 'tiempo' => 'required|numeric',
                 'descripcion' => 'required|string',
                 'foto' => 'required',

             ]);
             $input = $request->all();
             $servicio=new Servicio($input);
             $servicio->save();
             if($request->hasFile('foto')){
                 $file=$request->file('foto');
                 $filename = $fileName = time() . '_' . $file->getClientOriginalName();
                 $file->move('storage/', $filename);
                 $servicio->foto = $filename;
                 $servicio->save();
             }
            return $servicio;
         }catch (\Exception $exception) {
             return response()->json(['error' => $exception->getMessage()]);
         }
     }
}
