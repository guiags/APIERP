<?php

namespace App\Http\Controllers\Api;

use App\Models\Histpedido;
use App\Models\Banco;
use App\Models\Histpedidoitens;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\StoreHistpedidoRequest;
use App\Http\Requests\StoreHistpedidoitensRequest;
use App\Http\Resources\HistpedidoResource;
use Illuminate\Foundation\Http\FormRequest;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;

class HistpedidoController extends Controller
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
                $intervalo = $request->query('intervalo');
                $pedidos = HistPedido::with('itens','itens.produto'/*, 'itens.produto.lotes', 'itens.grade'*/);
    
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
                $pedidos = $pedidos->get();
                
                $this->rolbackDatabaseConnection();
                
                /*$pedidos->each(function ($pedido) {
                    $pedido->itens->each(function ($item) {
                        $item->makeHidden('idpedido');
                    });
                });*/
                return HistPedidoResource::collection($pedidos);
            /*$pedidos = Histpedido::with('itens')->get();
            $this->rolbackDatabaseConnection();
            
            $pedidos->each(function ($pedido) {
                $pedido->itens->each(function ($item) {
                    $item->makeHidden('idpedido');
                });
            });
            //return response()->json($produtos);
            return HistpedidoResource::collection($pedidos);*/    
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
            $pedidos = $request->input('itens');
            foreach($pedidos as $pedido){
                Histpedidoitens::create($pedido);
            }

            $pedido = Histpedido::create($request->except('itens'));
            $this->rolbackDatabaseConnection();
            return response($request);//new ProdutoResource($produto);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, $id )
    {
        try {
            $aux = $this->changeDatabaseConnection($request);
            if(!$aux){
                return response()->json(['erro' => '404',
                'message' => 'Token Invalido.',
                                ], 404);   
            }
            else{
                $pedido = Histpedido::with('itens')->find($id);
                if(!$pedido){
                    $this->rolbackDatabaseConnection();
                    return response()->json(['erro' => '404',
                    'message' => 'Pedido nao encontrado.',
                                        ], 404);
                }
                else{
                    $this->rolbackDatabaseConnection();
                    return new HistpedidoResource($pedido);
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
    /*public function edit(Histpedido $histpedido)
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
            $pedido = DB::table('hist_pedido')->where('id', $id)->first();
            if (!$pedido) {
                return response()->json(['erro' => '404',
                'message' => 'Pedido nao encontrado.',
                                    ], 404);
            }
            $pedidoitem = DB::table('hist_pedido_itens')->where('idpedido', $id)->first();
            if ($pedidoitem) {
                DB::table('hist_pedido_itens')->where('idpedido', $id)->delete();
            }


            $itens = $request->input('itens');
            foreach($itens as $item){
                Histpedidoitens::create($item);
            }


            DB::table('hist_pedido')
            ->where('id', $id)
            ->update($request->only(['id', 'emissao', 'status', 'idcliente', 'entrega', 'percdesc', 'vrdesc', 'vrbruto', 'vrliquido', 'obs', 'idformapag1', 'idformapag2', 'idplanopag1', 'idplanopag2', 'vrpago1', 'vrpago2', 'idvendedor', 'vrcomis', 'perccomis']));  

            $this->rolbackDatabaseConnection();
            return new HistpedidoResource($request);
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
            $pedidoitem = DB::table('hist_pedido_itens')->where('idpedido', $id)->first();
            if ($pedidoitem) {
                DB::table('hist_pedido_itens')->where('idpedido', $id)->delete();
            }

            $pedido= DB::table('hist_pedido')->where('id', $id)->first();
            if (!$pedido) {
                return response()->json(['erro' => '404',
                'message' => 'Pedido nao encontrado.',
                                    ], 404);
            } 
            DB::table('hist_pedido')->where('id', $id)->delete();

            $this->rolbackDatabaseConnection();
            return response()->json(['codigo' => '204',
                'message' => 'Pedido excluido.',
                                ], 204);
        }
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
