<?php

namespace App\Http\Controllers\Api;

use App\Models\Configuracao;
use App\Models\Banco;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\StoreConfiguracaoRequest;
use App\Http\Resources\ConfiguracaoResource;
use Illuminate\Foundation\Http\FormRequest;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;

class ConfiguracaoController extends Controller
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
            $configuracao = Configuracao::all();
            $this->rolbackDatabaseConnection();
            return ConfiguracaoResource::collection($configuracao);    
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
            $configuracao = Configuracao::create($request->all());
            $this->rolbackDatabaseConnection();
            return new ConfiguracaoResource($configuracao);
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
                $configuracao = Configuracao::find($id);
                if(!$configuracao){
                    return response()->json(['erro' => '404',
                    'message' => 'Configuracao nao encontrada.',
                                        ], 404);
                }
                else{
                    $this->rolbackDatabaseConnection();
                    return new ConfiguracaoResource($configuracao);
                }
            }

        } catch (ModelNotFoundException $e) {
            $this->rolbackDatabaseConnection();
            return response()->json(['erro' => '404',
            'message' => 'Configuracao nao encontrada.',
            ], 404);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    /*public function edit(Configuracao $configuracao)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $aux = $this->changeDatabaseConnection($request);
        if(!$aux){
            return response()->json(['erro' => '404',
            'message' => 'Token Invalido.',
                                ], 404);
        }
        else{
            $configuracao = DB::table('configuracoes')->where('id', $id)->first();
            if (!$configuracao) {
                return response()->json(['erro' => '404',
                'message' => 'Configuracao nao encontrada.',
                                    ], 404);
            } 
            DB::table('configuracoes')
            ->where('id', $id)
            ->update($request->only(['usacomisregresdesc', 'utilizaindicepreco', 'validaestneg', 'bloqestneg', 'bloqclidebvenc', 'bloqclilimitediasvenc', 'bloqclidiasvencatecor', 'bloqclidiasvencaposcor', 'precocasasdecimais', 'adicobsclinoped', 'carregalistaprodvazia']));   

            $this->rolbackDatabaseConnection();
            return new ConfiguracaoResource($configuracao);
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
            $configuracao = DB::table('configuracoes')->where('id', $id)->first();
            if (!$configuracao) {
                return response()->json(['erro' => '404',
                'message' => 'Configuracao nao encontrada.',
                                    ], 404);
            } 
            DB::table('configuracoes')->where('id', $id)->delete();
            $this->rolbackDatabaseConnection();
            return response()->json(['erro' => '204',
                'message' => 'Configuracao excluida.',
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
