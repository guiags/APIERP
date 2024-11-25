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
        $aux = $this->changeDatabaseConnection($request);
        if (!$aux){
            return response()->json(['erro' => '404',
            'message' => 'Token Invalido.',
                            ], 404);
        }
        else{
            $pedidos = Pedido::with('itens')->get();
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
                $auxiliar = $pedido['id'];
                DB::beginTransaction();   
                try{ 
                    $itens = $pedido['itens'];
                    foreach($itens as $item){
                        Pedidoitens::create($item);
                    }
                    Pedido::create($pedido);
                    DB::commit();
                    array_push($responsecodssuc, $auxiliar);
                } catch (\Exception $e) {
                    DB::rollback();
                    $auxiliar = ['Numped' => $auxiliar];
                    array_push($responsecodsermes, [$auxiliar,$e]);
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
                $pedido = Pedido::with('itens')->find($id);
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

    public function changeDatabaseConnection(Request $request)
    {
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
