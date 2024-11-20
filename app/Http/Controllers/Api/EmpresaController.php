<?php

namespace App\Http\Controllers\Api;

use App\Models\Empresa;
use App\Models\Banco;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\StoreEmpresaRequest;
use App\Http\Resources\EmpresaResource;
use Illuminate\Foundation\Http\FormRequest;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;

class EmpresaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $aux = $this->changeDatabaseConnection($request);
        if (!$aux){
            return response()->json(['codigo' => '404',
                'message' => 'Token Invalido.',
                                ], 404); 
        }
        else{
            $empresa = Empresa::all();
            $this->rolbackDatabaseConnection();
            return EmpresaResource::collection($empresa);    
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
            return response()->json(['codigo' => '404',
                'message' => 'Token Invalido.',
                                ], 404); 
        }
        else{
            $empresa = Empresa::create($request->all());
            $this->rolbackDatabaseConnection();
            return new EmpresaResource($empresa);
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
                return response()->json(['codigo' => '404',
                'message' => 'Token Invalido.',
                                ], 404);   
            }
            else{
                $empresa = Empresa::find($id);
                if(!$empresa){
                    return response()->json(['codigo' => '404',
                    'message' => 'Empresa nao encontrada.',
                                        ], 404);
                }
                else{
                    $this->rolbackDatabaseConnection();
                    return new EmpresaResource($empresa);
                }
            }

        } catch (ModelNotFoundException $e) {
            $this->rolbackDatabaseConnection();
            return response()->json(['codigo' => '404',
            'message' => 'Empresa nao encontrada.',
            ], 404);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    /*public function edit(Empresa $empresa)
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
            return response()->json(['codigo' => '404',
            'message' => 'Token Invalido.',
                                ], 404);
        }
        else{
            $empresa = DB::table('empresa')->where('id', $id)->first();
            if (!$empresa) {
                return response()->json(['codigo' => '404',
                'message' => 'Empresa nao encontrada.',
                                    ], 404);
            } 
            DB::table('empresa')
            ->where('id', $id)
            ->update($request->only(['nomefantasia', 'endereco', 'bairro', 'cidade', 'uf', 'telefone', 'celular', 'cnpj']));   

            //$cliente->update($request->all());
            $this->rolbackDatabaseConnection();
            return new EmpresaResource($empresa);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, $id)
    {
        $aux = $this->changeDatabaseConnection($request);
        if(!$aux){
            return response()->json(['codigo' => '404',
            'message' => 'Token Invalido.',
                                ], 404);
        }
        else{
            $empresa = DB::table('empresa')->where('id', $id)->first();
            if (!$empresa) {
                return response()->json(['codigo' => '404',
                'message' => 'Empresa nao encontrada.',
                                    ], 404);
            } 
            DB::table('empresa')->where('id', $id)->delete();
            $this->rolbackDatabaseConnection();
            return response()->json(['codigo' => '204',
                'message' => 'Empresa excluida.',
                                ], 204);
        }
    }

    public function changeDatabaseConnection(Request $request)
    {
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
