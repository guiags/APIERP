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
            return response()->json(['erro' => '404',
            'message' => 'Token Invalido.',
                            ], 404);
        }
        else{
            $idvendedor = $request->query('idvendedor');
            //$pedidos = HistPedido::with('itens');

            if(!empty($idvendedor)){
                $clientes = Cliente::where('idvendedor', $idvendedor)->get();
            }else{
                $clientes = Cliente::all();
            }

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
            return response()->json(['erro' => '404',
            'message' => 'Token Invalido.',
                            ], 404);
        }
        else{
            /*$cliente = Cliente::create($request->all());
            $this->rolbackDatabaseConnection();
            return new ClienteResource($cliente);*/
            $clientes = $request->input('data');
            $auxiliar = 0;
            $responsecodssuc=[];
            $responsecodsermes=[];
            foreach($clientes as $cliente){
                $vaidationerrors=[];
                $auxiliar = $cliente['cpfcnpj'];
                DB::beginTransaction();   
                try{ 
                    $vercpf = DB::table('clientes')->where('cpfcnpj', $auxiliar)->first();
                    if(!$vercpf){
                        Cliente::create($cliente);
                        DB::commit();
                        array_push($responsecodssuc, $auxiliar);
                    }
                    else{
                        $auxiliar = ['cpfcnpj' => $auxiliar,
                                        'erro' => 'O cpf/cpnj já consta na base.'];
                        array_push($responsecodsermes, $auxiliar);    
                    }                    
                } catch (\Exception $e) {
                    DB::rollback();
                    if($e->errorInfo[1] == 1062){
                        $auxiliar = ['cpfcnpj' => $auxiliar,
                                'message'=> 'O Cliente já consta na base de dados.'];    
                    }else{
                        if($cliente['cpfcnpj'] == null){
                            array_push($vaidationerrors, 'cpfcnpj');
                        }
                        if($cliente['celular1'] == null){
                            array_push($vaidationerrors, 'celular1');;
                        }
                        if($cliente['logradouro'] == null){
                            array_push($vaidationerrors, 'logradouro');;
                        }
                        if($cliente['bairro'] == null){
                            array_push($vaidationerrors, 'bairro');;
                        }
                        if($cliente['cidade'] == null){
                            array_push($vaidationerrors, 'cidade');;
                        }
                        if($cliente['uf'] == null){
                            array_push($vaidationerrors, 'uf');;
                        }
                        if($cliente['idvendedor'] == null){
                            array_push($vaidationerrors, 'idvendedor');;
                        }
                        if(empty($vaidationerrors)){
                            //return $e->errorInfo[2];
                            $auxiliar = ['cpfcnpj' => $auxiliar,
                                'message'=> $e->errorInfo[2]];
                        }
                        else{
                            $auxiliarnulos ='';
                            foreach($vaidationerrors as $ercamp){
                                $auxiliarnulos = $auxiliarnulos . $ercamp;
                                if($ercamp == $vaidationerrors[array_key_last($vaidationerrors)]){
                                    $auxiliarnulos = $auxiliarnulos .'.';  
                                }else{
                                    $auxiliarnulos = $auxiliarnulos .', ';  
                                }
                            }

                            if($auxiliar==null){
                                $auxiliar = ['nome' => $cliente['nomepessoa'],
                                'message'=> "Os seguintes campos não estão preenchidos: ". $auxiliarnulos];
                            }else{
                                $auxiliar = ['cpfcnpj' => $auxiliar,
                                'message'=> "Os seguintes campos não estão preenchidos: ". $auxiliarnulos];
                            }
                        }
                    }
                    array_push($responsecodsermes, $auxiliar);
                }   
            }
            $this->rolbackDatabaseConnection();
            if (empty($responsecodsermes)){
                return response()->json([
                    'sucesso' => $responsecodssuc], 200);        
            }
            return response()->json([
                'erro' => $responsecodsermes,
                    'sucesso' => $responsecodssuc], 200);  
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
                return response()->json(['erro' => '404',
                'message' => 'Token Invalido.',
                                ], 404);    
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
            return response()->json(['erro' => '404',
            'message' => 'Token Invalido.',
                            ], 404);;
        }
        else{
            $cliente = DB::table('clientes')->where('codpessoa', $id)->first();
            if (!$cliente) {
                return response()->json(['erro' => '404',
                'message' => 'Cliente nao encontrado.',
                                    ], 404);
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
            return response()->json(['erro' => '404',
            'message' => 'Token Invalido.',
                            ], 404);
        }
        else{
            $cliente = DB::table('clientes')->where('codpessoa', $id)->first();
            if (!$cliente) {
                $this->rolbackDatabaseConnection();
                return response()->json(['erro' => '404',
                'message' => 'Token Invalido.',
                                ], 404);
            } 
            DB::table('clientes')->where('codpessoa', $id)->delete();
            //$cliente->delete();
            $this->rolbackDatabaseConnection();
            return response()->json(['codigo' => '204',
                'message' => 'Cliente excluido.',
                                ], 204);
        }
    }

    public function showByCpfCnpj($cpfcnpj, Request $request)
    {
        // Busca o usuário pelo email
        $aux = $this->changeDatabaseConnection($request);
        //return $aux;
         if(!$aux){
        return response()->json(['erro' => '404',
        'message' => 'Token Invalido.',
                        ], 404);
        }
        $cliente = Cliente::where('cpfcnpj', $cpfcnpj)->first();
        $this->rolbackDatabaseConnection();
        // Verifica se o usuário foi encontrado
        if ($cliente) {

            return response()->json($cliente);  // Retorna o usuário em formato JSON
        }

    // Se não encontrar, retorna uma mensagem de erro
        return response()->json(['erro' => '404',
            'message' => 'CPF ou CNPJ nao encontrados.',
                                ], 404);
    }

    public function destroyByCpfCnpj($cpfcnpj, Request $request)
    {
        $aux = $this->changeDatabaseConnection($request);
         if(!$aux){
        return response()->json(['erro' => '404',
        'message' => 'Token Invalido.',
                        ], 404);
        }
        $cliente = DB::table('clientes')->where('cpfcnpj', $cpfcnpj)->first();
        if (!$cliente) {
            $this->rolbackDatabaseConnection();
            return response()->json(['erro' => '404',
            'message' => 'Cliente nao encontrado.',
                                ], 404);
        } 
        DB::table('clientes')->where('cpfcnpj', $cpfcnpj)->delete();
        $this->rolbackDatabaseConnection();
        return response()->json(['codigo' => '204',
                'message' => 'Cliente excluido.',
                                ], 204);
    }

    public function updateByCpfCnpj($cpfcnpj, Request $request)
    {
        $aux = $this->changeDatabaseConnection($request);
         if(!$aux){
        return response('Token Inválido', 404);
        }
        $cliente = DB::table('clientes')->where('cpfcnpj', $cpfcnpj)->first();
        if (!$cliente) {
            return response()->json(['erro' => '404',
            'message' => 'Cliente nao encontrado.',
                                ], 404);
        }
        else{
            DB::table('clientes')
            ->where('cpfcnpj', $cpfcnpj)
            ->update($request->only(['nomepessoa', 'tipopessoa', 'cpfcnpj', 'inscestadual', 'email', 'telefone1', 'telefone2', 'celular1', 'celular2', 'logradouro', 'numero', 'complemento', 'bairro', 'cidade', 'uf', 'cep', 'obs', 'datadocvenc', 'bloqueado', 'obsbloq', 'idvendedor', 'novo']));   
            $this->rolbackDatabaseConnection();
            return new ClienteResource($cliente);
        }
    }


    public function changeDatabaseConnection(Request $request)
    {
        $this->rolbackDatabaseConnection();
        $TokenRenovar = $request->header('TokenRenovar');
        $NomeBanco = Banco::where('TokenRenovar', $TokenRenovar)->Pluck('NomeBanco');
        //$UserBanco = Banco::where('TokenRenovar', $TokenRenovar)->Pluck('usuario');
        //$SenhaBanco = Banco::where('TokenRenovar', $TokenRenovar)->Pluck('senha');

        $NomeBanco = preg_replace('/["\[\]]/', '', $NomeBanco);
        //$UserBanco = preg_replace('/["\[\]]/', '', $UserBanco);
        //$SenhaBanco = preg_replace('/["\[\]]/', '', $SenhaBanco);

        if($NomeBanco!=null){
            Config::set('database.connections.mysql.database', $NomeBanco);
           // Config::set('database.connections.mysql.username', $UserBanco);
            //Config::set('database.connections.mysql.password', $SenhaBanco);
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
        //Config::set('database.connections.mysql.username', 'renovarp_master');
        //Config::set('database.connections.mysql.password', 'UehUySKE?QGSu9p');
        DB::connection('mysql')->reconnect();
    }
}
