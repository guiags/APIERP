<?php

namespace App\Http\Controllers\Api;

use App\Models\Cliente;
use App\Models\Banco;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\StoreClienteRequest;
use App\Http\Resources\ClienteResource;
use Illuminate\Foundation\Http\FormRequest;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;


class ClienteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $aux = $this->changeDatabaseConnection($request);
        if (!$aux){
            return response('Token Inválido', 404);
        }
        else{
            $clientes = Cliente::all();
            $this->rolbackDatabaseConnection();
            return ClienteResource::collection($clientes);    
        }    
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
        $aux = $this->changeDatabaseConnection($request);
        if(!$aux){
            return response('Token Inválido', 404);
        }
        else{
            $cliente = Cliente::create($request->all());
            $this->rolbackDatabaseConnection();
            return new ClienteResource($cliente);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id, Request $request)
    {
        try {
            $aux = $this->changeDatabaseConnection($request);
            if(!$aux){
                return response('Token Inválido', 404);    
            }
            else{
                $cliente = Cliente::findOrFail($id);
                if(!$cliente){
                    response('Cliente não encontrado!', 404)->json([
                        'message' => 'Cliente não encontrado.'
                    ], 404);
                }
                else{
                    $this->rolbackDatabaseConnection();
                    return new ClienteResource($cliente);
                }
            }

        } catch (ModelNotFoundException $e) {
            $this->rolbackDatabaseConnection();
            return response()->json([
                'message' => 'Cliente não encontrado.' + $e
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
    public function update(Request $request, $id)
    {
        /*$aux = $this->changeDatabaseConnection($request);
        if(!$aux){
            return response('Token Inválido', 404);
        }
        else{
            $cliente->update($request->all());
            $this->rolbackDatabaseConnection();
            return new ClienteResource($cliente);
        }Adicionar Cliente $Cliente */
        $aux = $this->changeDatabaseConnection($request);
        if(!$aux){
            return response('Token Inválido', 404);
        }
        else{
            $cliente = DB::table('clientes')->where('codpessoa', $id)->first();
            if (!$cliente) {
                return response('Cliente não encontrado', 404);
            } 
            DB::table('clientes')
            ->where('codpessoa', $id)
            ->update($request->only(['codpessoa', 'nomepessoa', 'tipopessoa', 'cpfcnpj', 'inscestadual', 'email', 'telefone1', 'telefone2', 'celular1', 'celular2', 'logradouro', 'numero', 'complemento', 'bairro', 'cidade', 'uf', 'cep', 'obs', 'datadocvenc', 'bloqueado', 'obsbloq', 'idvendedor', 'novo']));   

            //$cliente->update($request->all());
            $this->rolbackDatabaseConnection();
            return new ClienteResource($cliente);
        }


    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id, Request $request)
    {
            
        $aux = $this->changeDatabaseConnection($request);
        //return $aux;
        if(!$aux){
            return response('Token Inválido', 404);
        }
        else{
            $cliente = DB::table('clientes')->where('codpessoa', $id)->first();
            if (!$cliente) {
                return response('Cliente não encontrado', 404);
            } 
            DB::table('clientes')->where('codpessoa', $id)->delete();
            //$cliente->delete();
            $this->rolbackDatabaseConnection();
            return response('Erro ao excluir', 204);
        }
    }

    public function showByCpfCnpj($cpfcnpj, Request $request)
    {
        // Busca o usuário pelo email
        $aux = $this->changeDatabaseConnection($request);
        //return $aux;
         if(!$aux){
        return response('Token Inválido', 404);
        }
        $cliente = Cliente::where('cpfcnpj', $cpfcnpj)->first();
        $this->rolbackDatabaseConnection();
        // Verifica se o usuário foi encontrado
        if ($cliente) {

            return response()->json($cliente);  // Retorna o usuário em formato JSON
        }

    // Se não encontrar, retorna uma mensagem de erro
        return response()->json(['codigo' => '404',
            'message' => 'CPF ou CNPJ nao encontrados.',
                                ], 404);
    }


    public function changeDatabaseConnection(Request $request)
    {
        $TokenRenovar = $request->header('TokenRenovar');
        $NomeBanco = Banco::where('TokenRenovar', $TokenRenovar)->Pluck('NomeBanco');
        $UserBanco = Banco::where('TokenRenovar', $TokenRenovar)->Pluck('usuario');
        $SenhaBanco = Banco::where('TokenRenovar', $TokenRenovar)->Pluck('senha');

        $NomeBanco = preg_replace('/["\[\]]/', '', $NomeBanco);
        $UserBanco = preg_replace('/["\[\]]/', '', $UserBanco);
        $SenhaBanco = preg_replace('/["\[\]]/', '', $SenhaBanco);

        if($NomeBanco!=null){
            Config::set('database.connections.mysql.database', $NomeBanco);
            Config::set('database.connections.mysql.username', $UserBanco);
            Config::set('database.connections.mysql.password', $SenhaBanco);
            DB::connection('mysql')->reconnect();

            return $NomeBanco;    
        }
        else{
            return false;
        }
    }

    public function rolbackDatabaseConnection()
    {
        Config::set('database.connections.mysql.database', 'renovarp_tokenmobile');
        Config::set('database.connections.mysql.username', 'renovarp_master');
        Config::set('database.connections.mysql.password', 'UehUySKE?QGSu9p');
        DB::connection('mysql')->reconnect();
    }
}
