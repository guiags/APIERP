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

class NavegadorRequestController extends Controller
{
    public function indexNavegador(Request $request)
    {
    $aux = $this->changeDatabaseConnection($request);
        if (!$aux){
            return response()->json(['erro' => '404',
            'message' => 'Token Invalido.',
                            ], 404);
        }
        else{
            $dt_modificacao = $request->query('dtmodificacao');
            $produtos = Produto::with('precos', 'lotes', 'grades');
            if(!empty($dt_modificacao)){
                $dt_modificacao = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $dt_modificacao);

                //return response()->json([$dataInicio->toDateString(), $dataFim->toDateString()]);
                $produtos->where('dtmodificacao', '>', $dt_modificacao->toDateTimeString());    
            }

            if($request->header('perpage') == null){
                $produtos = $produtos->get();
            }
            else{
                $perPage = $request->input('per_page', $request->header('perpage'));
                $produtos = $produtos->paginate($perPage);
            }
            $this->rolbackDatabaseConnection();
    
            return ProdutoResource::collection($produtos);    
        } 
    }

    public function changeDatabaseConnection(Request $request)
    {
        $this->rolbackDatabaseConnection();
        $TokenRenovar = $request->query('TokenRenovar');
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