<?php

namespace App\Http\Controllers;

use App\Models\Aluguer;
use App\Http\Requests\StoreAluguerRequest;
use App\Http\Requests\UpdateAluguerRequest;
use App\Repositories\AluguerRepository;
use Illuminate\Http\Request;

class AluguerController extends Controller
{
    public $aluguer;

    public function __construct(Aluguer $aluguer) {
        $this->aluguer = $aluguer;
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {

        $aluguerRepository = new AluguerRepository($this->aluguer);

        if($request->has('filtro')) {
           $aluguerRepository->filtro($request->filtro);
        }

        if($request->has('atributos')) {
            $aluguerRepository->selectAtributos($request->atributos);
        }

        return response()->json($aluguerRepository->getResultado(), 200);
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
        

        $request->validate($this->aluguer->rules());
        //$aluguer = aluguer::create($request->all());

        $aluguer = $this->aluguer->create([
            'cliente_id' => $request->cliente_id,
            'carro_id' => $request->carro_id,
            'data_inicio_periodo' => $request->data_inicio_periodo,
            'data_final_previsto_periodo' => $request->data_final_previsto_periodo,
            'data_final_realizado_periodo' => $request->data_final_realizado_periodo,
            'valor_diaria' => $request->valor_diaria,
            'km_inicial' => $request->km_inicial,
            'km_final' => $request->km_final
        ]);
        
        return response()->json($aluguer, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)  
    {
        $aluguer = $this->aluguer->find($id);
    
        if($aluguer === null) {
            return response()->json(['erro' => 'Recurso pesquisado nao existe.'], 404);
        }

        return response()->json($aluguer, 200);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Aluguer $aluguer)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id) {

        $aluguer = $this->aluguer->find($id);
        if($aluguer === null) {
            return response()->json(['erro' => 'Recurso solicitado nao existe.'], 404);
        }
        
        if($request->method() === 'PATCH'){

            $regrasDinamicas = array();

            
            foreach($aluguer->rules() as $input => $regra) {

                
                if(array_key_exists($input, $request->all())) {
                    $regrasDinamicas[$input] = $regra;
                }
            }

            $request->validate($regrasDinamicas);
        } else {
            $request->validate($aluguer->rules());
        }
       
        $aluguer->fill($request->all());
        $aluguer->save();
 
        return response()->json($aluguer, 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id) 
    {
        $aluguer = $this->aluguer->find($id);
        if($aluguer === null) {
            return response()->json(['erro' => 'Recurso pesquisado nao existe.'], 404);
        }

        $aluguer->delete();
        return response()->json(['msg' => 'O aluguer foi removida com Sucesso'], 200);
    }
}
