<?php

namespace App\Http\Controllers\Api;

use App\Models\Formapag;
use App\Models\Banco;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\StoreFormapagRequest;
use App\Http\Resources\FormapagResource;
use Illuminate\Foundation\Http\FormRequest;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;

class FormapagController extends Controller
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
            $formapag = Formapag::all();
            $this->rolbackDatabaseConnection();
            return FormapagResource::collection($formapag);    
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
            $formapag = Formapag::create($request->all());
            $this->rolbackDatabaseConnection();
            return new FormapagResource($formapag);
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
                $formapag = Formapag::find($id);
                if(!$formapag){
                    return response()->json(['codigo' => '404',
                    'message' => 'Forma de pagamento nao encontrada.',
                                        ], 404);
                }
                else{
                    $this->rolbackDatabaseConnection();
                    return new FormapagResource($formapag);
                }
            }

        } catch (ModelNotFoundException $e) {
            $this->rolbackDatabaseConnection();
            return response()->json(['codigo' => '404',
            'message' => 'Forma de pagamento nao encontrada.',
            ], 404);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    /*public function edit(Formapag $formapag)
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
            $formapag = DB::table('formaspag')->where('id', $id)->first();
            if (!$formapag) {
                return response()->json(['codigo' => '404',
                'message' => 'Forma de pagamento nao encontrada.',
                                    ], 404);
            } 
            DB::table('formaspag')
            ->where('id', $id)
            ->update($request->only(['descricao', 'avista']));   

            //$cliente->update($request->all());
            $this->rolbackDatabaseConnection();
            return new FormapagResource($formapag);
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
            $formapag = DB::table('formaspag')->where('id', $id)->first();
            if (!$formapag) {
                return response()->json(['codigo' => '404',
                'message' => 'Forma de pagamento nao encontrada.',
                                    ], 404);
            } 
            DB::table('formaspag')->where('id', $id)->delete();
            $this->rolbackDatabaseConnection();
            return response()->json(['codigo' => '204',
                'message' => 'Forma de pagamento excluida.',
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
