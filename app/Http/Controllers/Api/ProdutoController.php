<?php

namespace App\Http\Controllers\Api;

use App\Models\Produto;
use App\Models\Banco;
use App\Models\Produtopreco;
use App\Models\Produtolote;
use App\Models\Produtograde;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\StoreProdutoRequest;
use App\Http\Requests\StoreProdutoprecoRequest;
use App\Http\Resources\ProdutoResource;
use App\Http\Resources\ProdutoloteResource;
use Illuminate\Foundation\Http\FormRequest;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;

class ProdutoController extends Controller
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
            $dt_modificacao = $request->header('dt_modificacao');
            $produtos = Produto::with('precos', 'lotes', 'grades');
            if(!empty($dt_modificacao)){
                $dt_modificacao = \Carbon\Carbon::createFromFormat('Y-m-d', $dt_modificacao);

                //return response()->json([$dataInicio->toDateString(), $dataFim->toDateString()]);
                $produtos->where('dt_modificacao', '>', $dt_modificacao->toDateString());    
            }

            if($request->header('perpage') == null){
                $produtos = $produtos->get();
            }
            else{
                $perPage = $request->input('per_page', $request->header('perpage'));
                $produtos = $produtos->paginate($perPage);
            }
            $this->rolbackDatabaseConnection();
            
            /*$produtos->each(function ($produto) {
                $produto->precos->each(function ($preco) {
                    $preco->makeHidden('codprod');
                });
            });*/
            /*$produtos->each(function ($produto) {
                $produto->lotes->each(function ($lote) {
                    $lote->makeHidden('codprod');
                });
            });*/
            /*$produtos->each(function ($produto) {
                $produto->grades->each(function ($grade) {
                    $grade->makeHidden('codprod');
                });
            });*/
            //$json = json_encode($produtos, JSON_NUMERIC_CHECK);
            //return response()->json($produtos);
            return ProdutoResource::collection($produtos);    
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
            $produtos = $request->input('data');
            $auxiliar = 0;
            $responsecodssuc=[];
            $responsecodsermes=[];
            foreach($produtos as $produto){
                $auxiliar = $produto['codprod'];
                DB::beginTransaction();   
                try{ 
                    $precos = $produto['precos'];
                    foreach($precos as $preco){
                        Produtopreco::create($preco);
                    }
                    $lotes = $produto['lotes'];
                    foreach($lotes as $lote){
                        Produtolote::create($lote);
                    }
                    $grades = $produto['grades'];
                    foreach($grades as $grade){
                        Produtograde::create($grade);
                    }
                    $prod = Produto::create($produto);
                    DB::commit();
                    array_push($responsecodssuc, $auxiliar);
                } catch (\Exception $e) {
                    DB::rollback();
                    if($e->errorInfo[1] == 1062){
                        $auxiliar = ['codprod' => $auxiliar,
                                'erro'=> 'O Produto já consta na base de dados.'];    
                    }else{
                        $auxiliar = ['codprod' => $auxiliar,
                                'message'=> $e->errorInfo[2]];
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
                $produto = Produto::with('precos', 'lotes', 'grades')->find($id);
                if(!$produto){
                    $this->rolbackDatabaseConnection();
                    return response()->json(['erro' => '404',
                    'message' => 'Produto nao encontrada.',
                                        ], 404);
                }
                else{
                    $this->rolbackDatabaseConnection();
                    return new ProdutoResource($produto);
                }
            }

        } catch (ModelNotFoundException $e) {
            $this->rolbackDatabaseConnection();
            return response()->json(['erro' => '404',
            'message' => 'Produto nao encontrado.',
            ], 404);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    /*public function edit(Produto $produto)
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
            $produto = DB::table('produtos')->where('codprod', $id)->first();
            if (!$produto) {
                return response()->json(['erro' => '404',
                'message' => 'Produto nao encontrado.',
                                    ], 404);
            }
            $produtopreco = DB::table('produtos_precos')->where('codprod', $id)->first();
            if ($produtopreco) {
                DB::table('produtos_precos')->where('codprod', $id)->delete();
            }

            $produtolote = DB::table('produtos_lotes')->where('codprod', $id)->first();
            if ($produtolote) {
                DB::table('produtos_lotes')->where('codprod', $id)->delete();
            }

            $produtograde = DB::table('produtos_grades')->where('codprod', $id)->first();
            if ($produtograde) {
                DB::table('produtos_grades')->where('codprod', $id)->delete();
            }

            $precos = $request->input('precos');
            foreach($precos as $preco){
                Produtopreco::create($preco);
            }

            $lotes = $request->input('lotes');
            foreach($lotes as $lote){
                Produtolote::create($lote);
            }

            $grades = $request->input('grades');
            foreach($grades as $grade){
                Produtograde::create($grade);
            }

            DB::table('produtos')
            ->where('codprod', $id)
            ->update($request->only(['codprod', 'nome', 'descrcompleta', 'referencia', 'codgrupo', 'unidade', 'codbarras', 'preco', 'fotoprod', 'usagrade', 'estoque', 'usalote', 'inativo', 'dt_modificacao']));  


            //return $precos;


            $this->rolbackDatabaseConnection();
            return $request;//new ProdutoResource($request);
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
            $produtopreco = DB::table('produtos_precos')->where('codprod', $id)->first();
            if ($produtopreco) {
                DB::table('produtos_precos')->where('codprod', $id)->delete();
            }

            $produtolote = DB::table('produtos_lotes')->where('codprod', $id)->first();
            if ($produtolote) {
                DB::table('produtos_lotes')->where('codprod', $id)->delete();
            }

            $produtograde = DB::table('produtos_grades')->where('codprod', $id)->first();
            if ($produtograde) {
                DB::table('produtos_grades')->where('codprod', $id)->delete();
            }

            $produto = DB::table('produtos')->where('codprod', $id)->first();
            if (!$produto) {
                return response()->json(['erro' => '404',
                'message' => 'Produto nao encontrado.',
                                    ], 404);
            } 
            DB::table('produtos')->where('codprod', $id)->delete();

            $this->rolbackDatabaseConnection();
            return response()->json(['codigo' => '204',
                'message' => 'Produto excluida.',
                                ], 204);
        }
    }


    public function showProdutosByCodprod(Request $request, $codprod)
    {
        $aux = $this->changeDatabaseConnection($request);
        if(!$aux){
        return response()->json(['erro' => '404',
        'message' => 'Token Invalido.',
                        ], 404);
        }
        $lotes= Produtolote::where('codprod', $codprod)->get();
        if (!$lotes) {
            $this->rolbackDatabaseConnection();
            return response()->json(['erro' => '404',
            'message' => 'Pedido nao encontrado.',
                                ], 404);
        }
        $this->rolbackDatabaseConnection();
        return ProdutoloteResource::collection($lotes);
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
