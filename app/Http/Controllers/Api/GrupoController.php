<?php

namespace App\Http\Controllers\Api;

use App\Models\Grupo;
use App\Models\Banco;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\StoreGrupoRequest;
use App\Http\Resources\GrupoResource;
use Illuminate\Foundation\Http\FormRequest;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;

class GrupoController extends Controller
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
            $grupo = Grupo::all();
            $this->rolbackDatabaseConnection();
            return GrupoResource::collection($grupo);    
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
            try{
                $grupo = Grupo::create($request->all());
                $this->rolbackDatabaseConnection();
                return new GrupoResource($grupo);
            } catch (\Exception $e) {
                if($e->errorInfo[1] == 1062){
                    return response()->json(['codgrupo' => $request->codgrupo,
                            'erro'=> 'O Grupo já consta na base de dados.']);    
                }else{
                    return response()->json(['id' => $request->id,
                            'message'=> $e->errorInfo[2]]);
                }
            }
            
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
                $grupo = Grupo::find($id);
                if(!$grupo){
                    response()->json(['erro' => '404',
                    'message' => 'Grupo nao encontrado.',
                                    ], 404);
                }
                else{
                    $this->rolbackDatabaseConnection();
                    return new GrupoResource($grupo);
                }
            }

        } catch (ModelNotFoundException $e) {
            $this->rolbackDatabaseConnection();
            return response()->json(['erro' => '404',
            'message' => 'Grupo nao encontrado.',
                                ], 404);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    /*public function edit(Grupo $grupo)
    {
        //
    }*/

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
            $grupo = DB::table('grupo')->where('codgrupo', $id)->first();
            if (!$grupo) {
                return response()->json(['erro' => '404',
                'message' => 'Grupo nao encontrado.',
                                    ], 404);
            } 
            DB::table('grupo')
            ->where('codgrupo', $id)
            ->update($request->only(['codgrupo','descricao','dir_imagem']));   

            $this->rolbackDatabaseConnection();
            return new GrupoResource($grupo);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id, Request $request)
    {
        $aux = $this->changeDatabaseConnection($request);
        if(!$aux){
            return response()->json(['erro' => '404',
            'message' => 'Token Invalido.',
                            ], 404); 
        }
        else{
            $grupo = DB::table('grupo')->where('codgrupo', $id)->first();
            if (!$grupo) {
                return response()->json(['erro' => '404',
                'message' => 'Grupo nao encontrado.',
                                    ], 404);
            } 
            DB::table('grupo')->where('codgrupo', $id)->delete();
            $this->rolbackDatabaseConnection();
            return response('Erro ao excluir', 204);
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
            //Config::set('database.connections.mysql.username', $UserBanco);
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
