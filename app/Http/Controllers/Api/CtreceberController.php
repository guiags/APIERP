<?php

namespace App\Http\Controllers\Api;

use App\Models\Ctreceber;
use App\Models\Banco;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\StoreCtreceberRequest;
use App\Http\Resources\CtreceberResource;
use Illuminate\Foundation\Http\FormRequest;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;

class CtreceberController extends Controller
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
            $ctreceber = Ctreceber::all();
            $this->rolbackDatabaseConnection();
            return CtreceberResource::collection($ctreceber);    
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
            $ctreceber = Ctreceber::create($request->all());
            $this->rolbackDatabaseConnection();
            return new CtreceberResource($ctreceber);
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
                $ctreceber = Ctreceber::find($id);
                if(!$ctreceber){
                    $this->rolbackDatabaseConnection();
                    return response()->json(['erro' => '404',
                    'message' => 'Conta a receber nao encontrada.',
                                        ], 404);
                }
                else{
                    $this->rolbackDatabaseConnection();
                    return new CtreceberResource($ctreceber);
                }
            }

        } catch (ModelNotFoundException $e) {
            $this->rolbackDatabaseConnection();
            return response()->json(['erro' => '404',
            'message' => 'Conta a receber nao encontrada.',
            ], 404);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    /*public function edit(Ctreceber $ctreceber)
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
            $ctreceber = DB::table('ctreceber')->where('coddoc', $id)->first();
            if (!$ctreceber) {
                $this->rolbackDatabaseConnection();
                return response()->json(['erro' => '404',
                'message' => 'Conta a receber nao encontrada.',
                                    ], 404);
            } 
            DB::table('ctreceber')
            ->where('coddoc', $id)
            ->update($request->only(['numdoc', 'dataemis', 'datavenc', 'codcli', 'nomecli', 'vrdoc', 'conta', 'planopag', 'datapago', 'vrpago', 'situacao']));   

            //$cliente->update($request->all());
            $this->rolbackDatabaseConnection();
            return new CtreceberResource($ctreceber);
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
            $ctreceber = DB::table('ctreceber')->where('coddoc', $id)->first();
            if (!$ctreceber) {
                $this->rolbackDatabaseConnection();
                return response()->json(['erro' => '404',
                'message' => 'Conta a receber nao encontrada.',
                                    ], 404);
            } 
            DB::table('ctreceber')->where('coddoc', $id)->delete();
            $this->rolbackDatabaseConnection();
            return response()->json(['erro' => '204',
                'message' => 'Conta a receber excluida.',
                                ], 204);
        }
    }

    public function destroyAll(Request $request)
    {
        $aux = $this->changeDatabaseConnection($request);
        if(!$aux){
            return response()->json(['erro' => '404',
            'message' => 'Token Invalido.',
                                ], 404);
        }
        else{
            DB::table('ctreceber')->delete();
            $this->rolbackDatabaseConnection();
            return response()->json(['erro' => '204',
                'message' => 'Todas as contas foram excluidas.',
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
