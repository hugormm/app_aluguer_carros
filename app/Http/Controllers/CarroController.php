<?php

namespace App\Http\Controllers;

use App\Models\Carro;
use App\Http\Requests\StoreCarroRequest;
use App\Http\Requests\UpdateCarroRequest;
use App\Repositories\CarroRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CarroController extends Controller
{
    public $carro;

    public function __construct(Carro $carro) {
        $this->carro = $carro;
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {

        $carroRepository = new CarroRepository($this->carro);

        if($request->has('atributos_modelo')) {
            $atributos_modelo = 'modelo:id,'.$request->atributos_modelo;
            $carroRepository->selectAtributosRegistosRelacionados($atributos_modelo);
        } else {
            $carroRepository->selectAtributosRegistosRelacionados('modelo');
        }

        if($request->has('filtro')) {
           $carroRepository->filtro($request->filtro);
        }

        if($request->has('atributos')) {
            $carroRepository->selectAtributos($request->atributos);
        }

        return response()->json($carroRepository->getResultadoPaginado(3), 200);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate($this->carro->rules());

        $carro = $this->carro->create([
            'modelo_id' => $request->modelo_id,
            'placa' => $request->placa,
            'disponivel' => $request->disponivel,
            'km' => $request->km
        ]);
        
        return response()->json($carro, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id) 
    {
        $carro = $this->carro->with('modelo')->find($id);
    
        if($carro === null) {
            return response()->json(['erro' => 'Recurso pesquisado nao existe.'], 404);
        }

        return response()->json($carro, 200);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Carro $carro)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id) {

        $carro = $this->carro->find($id);
        if($carro === null) {
            return response()->json(['erro' => 'Recurso solicitado nao existe.'], 404);
        }
        
        if($request->method() === 'PATCH'){

            $regrasDinamicas = array();

            foreach($carro->rules() as $input => $regra) {
                if(array_key_exists($input, $request->all())) {
                    $regrasDinamicas[$input] = $regra;
                }
            }

            $request->validate($regrasDinamicas);
        } else {
            $request->validate($carro->rules());
        }
       
        $carro->fill($request->all());
        $carro->save();
 
        return response()->json($carro, 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $carro = $this->carro->find($id);
        if($carro === null) {
            return response()->json(['erro' => 'Recurso pesquisado nao existe.'], 404);
        }

        $carro->delete();
        return response()->json(['msg' => 'O Carro foi removida com Sucesso'], 200);
    }
}
