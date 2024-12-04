<?php

namespace App\Http\Controllers\Api;

use App\Models\Pedido;
use App\Models\Banco;
use App\Models\Pedidoitens;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\StorePedidoRequest;
use App\Http\Requests\StorePedidoitensRequest;
use App\Http\Resources\PedidoResource;
use Illuminate\Foundation\Http\FormRequest;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;

class PedidoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $this->rolbackDatabaseConnection();
        $aux = $this->changeDatabaseConnection($request);
        if (!$aux){
            return response()->json(['erro' => '404',
            'message' => 'Token Invalido.',
                            ], 404);
        }
        else{
            $idvendedor = $request->query('idvendedor');
            $intervalo = $request->query('intervalo');
            $tipo = $request->query('tipo');
            $pedidos = Pedido::with('itens', 'cliente', 'itens.produto', 'itens.produto.lotes', 'itens.grade');

            if(!empty($intervalo)){
                list($dataInicio, $dataFim) = explode('_', $intervalo);
                $dataInicio = \Carbon\Carbon::createFromFormat('Y-m-d', $dataInicio);
                $dataFim = \Carbon\Carbon::createFromFormat('Y-m-d', $dataFim);
                //return response()->json([$dataInicio->toDateString(), $dataFim->toDateString()]);
                $pedidos->whereBetween('emissao', [$dataInicio->toDateString(), $dataFim->toDateString()]);    
            }
            if(!empty($idvendedor)){
                $pedidos->where('idvendedor', $idvendedor);
            }
            if(!empty($tipo)){
                $pedidos->where('tipo', $tipo);
            }
            $pedidos = $pedidos->get();
            
            $this->rolbackDatabaseConnection();
            
            $pedidos->each(function ($pedido) {
                $pedido->itens->each(function ($item) {
                    $item->makeHidden('idpedido');
                });
            });
            //return response()->json($produtos);
            return PedidoResource::collection($pedidos);    
        } 
    }

    /**
     * Show the form for creating a new resource.
     */
    /*public function create()
    {
        //
    }

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
            /*$pedidos = $request->input('itens');
            foreach($pedidos as $pedido){
                Pedidoitens::create($pedido);
            }

            $pedido = Pedido::create($request->except('itens'));
            $this->rolbackDatabaseConnection();
            return response($request);//new ProdutoResource($produto);*/
            $pedidos = $request->input('data');
            $auxiliar = 0;
            $responsecodssuc=[];
            $responsecodsermes=[];
            foreach($pedidos as $pedido){
                $vaidationiterrors=[];
                $vaidationerrors=[];
                $auxiliar = $pedido['id'];
                DB::beginTransaction();
                if($pedido['emissao'] == null){
                    array_push($vaidationerrors, 'emissao');
                }
                if($pedido['status'] == null){
                    array_push($vaidationerrors, 'status');
                }
                if($pedido['tipo'] == null){
                    array_push($vaidationerrors, 'tipo');
                }
                if($pedido['idcliente'] == null){
                    array_push($vaidationerrors, 'idcliente');
                }
                if($pedido['vrbruto'] == null){
                    array_push($vaidationerrors, 'vrbruto');
                }
                if($pedido['vrliquido'] == null){
                    array_push($vaidationerrors, 'vrliquido');
                }
                if($pedido['idvendedor'] == null){
                    array_push($vaidationerrors, 'idvendedor');;
                }
                if(empty($vaidationerrors)) {
                    
                }  
                try{ 
                    $itens = $pedido['itens'];
                    if(empty($vaidationerrors)) {
                        foreach($itens as $item){

                            if($item['idpedido'] == null){
                                array_push($vaidationiterrors, 'idpedido');
                            }
                            if($item['numitem'] == null){
                                array_push($vaidationiterrors, 'numitem');
                            }
                            if($item['idproduto'] == null){
                                array_push($vaidationiterrors, 'idproduto');
                            }
                            if($item['codpreco'] == null){
                                array_push($vaidationiterrors, 'codpreco');
                            }
                            if($item['quantidade'] == null){
                                array_push($vaidationiterrors, 'quantidade');
                            }
                            if($item['vrunit'] == null){
                                array_push($vaidationiterrors, 'vrunit');
                            }
                            if($item['vrtotal'] == null){
                                array_push($vaidationiterrors, 'vrtotal');
                            }
                            if($item['unidade'] == null){
                                array_push($vaidationiterrors, 'unidade');
                            }

                            Pedidoitens::create($item);
                        }
                    }
                    Pedido::create($pedido);
                    DB::commit();
                    array_push($responsecodssuc, $auxiliar);
                } catch (\Exception $e) {
                    DB::rollback();
                    

                    if(empty($vaidationerrors) && empty($vaidationtierrors)){
                        $auxiliar = ['Numped' => $auxiliar,
                            'message'=> $e->errorInfo[2]];
                    }else{
                        $auxiliarnulos ='';
                        $auxiliaritnulos ='';
                        foreach($vaidationerrors as $ercamp){
                            $auxiliarnulos = $auxiliarnulos . $ercamp;
                            if($ercamp == $vaidationerrors[array_key_last($vaidationerrors)]){
                                $auxiliarnulos = $auxiliarnulos .'.';  
                            }else{
                                $auxiliarnulos = $auxiliarnulos .', ';  
                            }
                        }
                        foreach($vaidationiterrors as $ercamp){
                            $auxiliaritnulos = $auxiliaritnulos . $ercamp;
                            if($ercamp == $vaidationiterrors[array_key_last($vaidationiterrors)]){
                                $auxiliaritnulos = $auxiliaritnulos .'.';  
                            }else{
                                $auxiliaritnulos = $auxiliaritnulos .', ';  
                            }
                        }
                        $auxiliar = ['pedido' => $auxiliar,
                            'message'=> "Os seguintes campos do pedido não estão preenchidos: ". $auxiliarnulos];
                        if($auxiliaritnulos!=''){
                            //array_push($auxiliar, 'Os seguintes campos dos itens não estão preenchidos: ' . $auxiliaritnulos); 
                            $auxiliar['message'] = $auxiliar['message'] . 'Os seguintes campos dos itens não estão preenchidos: ' . $auxiliaritnulos;
                        } 
                    }

                    array_push($responsecodsermes, $auxiliar);
                }   
            }
            $this->rolbackDatabaseConnection();
            return response()->json([
                                'erro' => $responsecodsermes,
                                'sucesso' => $responsecodssuc], 200); 



        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, $id)
    {
        try {
            $aux = $this->changeDatabaseConnection($request);
            if(!$aux){
                return response()->json(['erro' => '404',
                'message' => 'Token Invalido.',
                                ], 404);   
            }
            else{
                $pedido = Pedido::with('itens', 'cliente', 'itens.produto')->find($id);
                if(!$pedido){
                    $this->rolbackDatabaseConnection();
                    return response()->json(['erro' => '404',
                    'message' => 'Pedido nao encontrado.',
                                        ], 404);
                }
                else{
                    $this->rolbackDatabaseConnection();
                    return new PedidoResource($pedido);
                }
            }

        } catch (ModelNotFoundException $e) {
            $this->rolbackDatabaseConnection();
            return response()->json(['erro' => '404',
            'message' => 'Pedido nao encontrado.',
            ], 404);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    /*public function edit(Pedido $pedido)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $this->rolbackDatabaseConnection();
        $aux = $this->changeDatabaseConnection($request);
        if(!$aux){
            return response()->json(['erro' => '404',
            'message' => 'Token Invalido.',
                                ], 404);
        }
        else{
            $pedido = DB::table('pedido')->where('id', $id)->first();
            if (!$pedido) {
                return response()->json(['erro' => '404',
                'message' => 'Pedido nao encontrado.',
                                    ], 404);
            }
            $pedidoitem = DB::table('pedido_itens')->where('idpedido', $id)->first();
            if ($pedidoitem) {
                DB::table('pedido_itens')->where('idpedido', $id)->delete();
            }


            $itens = $request->input('itens');
            foreach($itens as $item){
                Pedidoitens::create($item);
            }


            DB::table('pedido')
            ->where('id', $id)
            ->update($request->only(['id', 'emissao', 'tipo', 'status', 'idcliente', 'entrega', 'percdesc', 'vrdesc', 'vrbruto', 'vrliquido', 'obs', 'idformapag1', 'idformapag2', 'idplanopag1', 'idplanopag2', 'vrpago1', 'vrpago2', 'idvendedor', 'vrcomis', 'perccomis']));  

            $this->rolbackDatabaseConnection();
            return new PedidoResource($request);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, $id)
    {
        $aux = $this->changeDatabaseConnection($request);
        if(!$aux){
            return response()->json(['erro' => '404',
            'message' => 'Token Invalido.',
                                ], 404);
        }
        else{
            $pedidoitem = DB::table('pedido_itens')->where('idpedido', $id)->first();
            if ($pedidoitem) {
                DB::table('pedido_itens')->where('idpedido', $id)->delete();
            }

            $pedido= DB::table('pedido')->where('id', $id)->first();
            if (!$pedido) {
                return response()->json(['erro' => '404',
                'message' => 'Pedido nao encontrado.',
                                    ], 404);
            } 
            DB::table('pedido')->where('id', $id)->delete();

            $this->rolbackDatabaseConnection();
            return response()->json(['codigo' => '204',
                'message' => 'Pedido excluido.',
                                ], 204);
        }
    }

    public function destroyByIdVendedor($idvendedor, Request $request)
    {
        $aux = $this->changeDatabaseConnection($request);
         if(!$aux){
        return response()->json(['erro' => '404',
        'message' => 'Token Invalido.',
                        ], 404);
        }
        $pedido= DB::table('pedido')->where('idvendedor', $idvendedor)->first();
        if (!$pedido) {
            $this->rolbackDatabaseConnection();
            return response()->json(['erro' => '404',
            'message' => 'Pedido nao encontrado.',
                                ], 404);
        }
        $tipo = $request->query('tipo');
        if(empty($tipo)){
            DB::table('pedido')->where('idvendedor', $idvendedor)->delete();
            DB::table('pedido_itens')->where('idvendedor', $idvendedor)->delete();
        }else{
            DB::table('pedido_itens')
                ->join('pedido', 'pedido_itens.idpedido', '=', 'pedido.id')  // Assumindo que 'idpedido' seja a chave de relacionamento
                ->where('pedido.tipo', $tipo)
                ->where('pedido.idvendedor', $idvendedor)
                ->delete();
            DB::table('pedido')->where('idvendedor', $idvendedor)->where('tipo', $tipo)->delete();
            //DB::table('pedido_itens')->where('idvendedor', $idvendedor)->delete();
            /*Pedidoitens::whereHas('pedido', function ($query) use ($tipo, $idvendedor){
                $query->where('tipo', $tipo)
                    ->where('pedido_itens.idpedido', '=', 'pedido.id')
                    ->where('idvendedor', $idvendedor);
            })->delete();*/
        }
        $this->rolbackDatabaseConnection();
        return response()->json(['codigo' => '204',
                'message' => 'Pedido excluido.',
                                ], 204);
    }

    public function showUltimoPedido(Request $request)
    {
        $aux = $this->changeDatabaseConnection($request);
        if(!$aux){
        return response()->json(['erro' => '404',
        'message' => 'Token Invalido.',
                        ], 404);
        }
        $pedido= Pedido::max('id');
        if (!$pedido) {
            $this->rolbackDatabaseConnection();
            return response()->json(['erro' => '404',
            'message' => 'Pedido nao encontrado.',
                                ], 404);
        }
        $this->rolbackDatabaseConnection();
        return response()->json(['numped' => $pedido,
                                ], 200);
    }


    public function changeDatabaseConnection(Request $request)
    {
        $this->rolbackDatabaseConnection();
        $TokenRenovar = $request->header('TokenRenovar');
        $NomeBanco = Banco::where('TokenRenovar', $TokenRenovar)->Pluck('NomeBanco');
        $NomeBanco = preg_replace('/["\[\]]/', '', $NomeBanco);
        if($NomeBanco!=null){
            Config::set('database.connections.mysql.database', $NomeBanco);
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
        DB::connection('mysql')->reconnect();
    }
}
