<?php

namespace App\Http\Controllers;

use App\Models\Marca;
use App\Repositories\MarcaRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MarcaController extends Controller
{
    public $marca;

    public function __construct(Marca $marca) {
        $this->marca = $marca;
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {

        $marcaRepository = new MarcaRepository($this->marca);

        if($request->has('atributos_modelos')) {
            $atributos_modelos = 'modelos:id,'.$request->atributos_modelos;
            $marcaRepository->selectAtributosRegistosRelacionados($atributos_modelos);
        } else {
            $marcaRepository->selectAtributosRegistosRelacionados('modelos');
        }

        if($request->has('filtro')) {
           $marcaRepository->filtro($request->filtro);
        }

        if($request->has('atributos')) {
            $marcaRepository->selectAtributos($request->atributos);
        }

        return response()->json($marcaRepository->getResultadoPaginado(10), 200);

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
        $request->validate($this->marca->rules(), $this->marca->feedback());
        $imagem = $request->file('imagem');
        $imagemUrn = $imagem->store('imagens', 'public');

        $marca = $this->marca->create([
            'nome' => $request->nome,
            'imagem' => $imagemUrn
        ]);
        
        return response()->json($marca, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)  
    {
        $marca = $this->marca->with('marcas')->find($id);
    
        if($marca === null) {
            return response()->json(['erro' => 'Recurso pesquisado nao existe.'], 404);
        }

        return response()->json($marca, 200);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Marca $marca)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id) 
    {
        $marca = $this->marca->find($id);
        if($marca === null) {
            return response()->json(['erro' => 'Recurso solicitado nao existe.'], 404);
        }
        
        if($request->method() === 'PATCH'){

            $regrasDinamicas = array();

            foreach($marca->rules() as $input => $regra) {
                if(array_key_exists($input, $request->all())) {
                    $regrasDinamicas[$input] = $regra;
                }
            }

            $request->validate($regrasDinamicas, $marca->feedback());
        } else {
            $request->validate($marca->rules(), $marca->feedback());
        }

        $marca->fill($request->all());

        if($request->file('imagem')) {
            Storage::disk('public')->delete($marca->imagem);

            $imagem = $request->file('imagem');
            $imagem_urn = $imagem->store('imagens', 'public');
            $marca->imagem = $imagem_urn;
        }

        $marca->save();

        return response()->json($marca, 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $marca = $this->marca->find($id);
        if($marca === null) {
            return response()->json(['erro' => 'Recurso pesquisado nao existe.'], 404);
        }

        Storage::disk('public')->delete($marca->imagem);

        $marca->delete();
        return response()->json(['msg' => 'A Marca foi removida com Sucesso'], 200);
    }
}
