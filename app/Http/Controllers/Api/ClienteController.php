<?php

namespace App\Http\Controllers\Api;

use App\Models\Cliente;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\StoreClienteRequest;
use App\Http\Resources\ClienteResource;
use Illuminate\Foundation\Http\FormRequest;


class ClienteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $clientes = Cliente::all();
        return ClienteResource::collection($clientes);
    }

    /**
     * Show the form for creating a new resource.
     */
    /*public function create()
    {
        //
    }*/

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {   
        echo $request;
        $cliente = Cliente::create($request->all());
        return new ClienteResource($cliente);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        try {
            // Buscar cliente pelo ID ou qualquer outra chave única (exemplo: codpessoa)
            $cliente = Cliente::findOrFail($id);

            // Se o cliente for encontrado, retornamos o recurso
            return new ClienteResource($cliente);

        } catch (ModelNotFoundException $e) {
            // Caso o cliente não seja encontrado, retornamos um erro 404
            return response()->json([
                'message' => 'Cliente não encontrado.'
            ], 404);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    /*public function edit(Cliente $cliente)
    {
        //
    }*/

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Cliente $cliente)
    {
        $cliente->update($request->all());
        return new ClienteResource($cliente);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Cliente $cliente)
    {
        $cliente->delete();
        return response('Erro ao excluir', 204);
    }
}
