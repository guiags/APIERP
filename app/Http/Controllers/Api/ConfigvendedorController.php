<?php

namespace App\Http\Controllers\Api;

use App\Models\Configvendedor;
use App\Models\Banco;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\StoreConfigvendedorRequest;
use App\Http\Resources\ConfigvendedorResource;
use Illuminate\Foundation\Http\FormRequest;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;

class ConfigvendedorController extends Controller
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
            $configvendedor = Configvendedor::all();
            $this->rolbackDatabaseConnection();
            return ConfigvendedorResource::collection($configvendedor);    
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
            $configvendedor = Configvendedor::create($request->all());
            $this->rolbackDatabaseConnection();
            return new ConfigvendedorResource($configvendedor);
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
                $configvendedor = Configvendedor::find($id);
                if(!$configvendedor){
                    return response()->json(['erro' => '404',
                    'message' => 'Configuracao nao encontrada.',
                                        ], 404);
                }
                else{
                    $this->rolbackDatabaseConnection();
                    return new ConfigvendedorResource($configvendedor);
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
    /*public function edit(Configvendedor $configvendedor)
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
            $configvendedor = DB::table('config_vendedor')->where('idvendedor', $id)->first();
            if (!$configvendedor) {
                return response()->json(['erro' => '404',
                'message' => 'Configuracao nao encontrada.',
                                    ], 404);
            } 
            DB::table('config_vendedor')
            ->where('idvendedor', $id)
            ->update($request->only(['exibeclibloq', 'listaprodvazia', 'naoexibeimgprod', 'sincronizacao', 'dt_ult_sinc']));   

            $this->rolbackDatabaseConnection();
            return new ConfigvendedorResource($configvendedor);
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
            $configvendedor = DB::table('config_vendedor')->where('idvendedor', $id)->first();
            if (!$configvendedor) {
                return response()->json(['erro' => '404',
                'message' => 'Configuracao nao encontrada.',
                                    ], 404);
            } 
            DB::table('config_vendedor')->where('idvendedor', $id)->delete();
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
