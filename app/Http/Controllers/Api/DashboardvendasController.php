<?php

namespace App\Http\Controllers\Api;

use App\Models\Dashboardvendas;
use App\Models\Banco;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\StoreDashboardvendasRequest;
use App\Http\Resources\DashboardvendasResource;
use Illuminate\Foundation\Http\FormRequest;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;

class DashboardvendasController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    /*public function index()
    {
        //
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
            //return $request[0]->get('idvendedor');
            DB::table('dashboard_vendas')->where('idvendedor', $request->input('data.0.idvendedor'))->delete();   
            
            $dashboards = $request->input('data');
            foreach($dashboards as $dashboard){
                Dashboardvendas::create($dashboard);
            }     


            $this->rolbackDatabaseConnection();
            return response()->json(['sucesso' => '200',
                'message' => 'Dashboards criados com sucesso!',
                            ], 200); 
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
                $dashboard = Dashboardvendas::where('idvendedor', $id)->get();
                if($dashboard->count() == 0){
                    $this->rolbackDatabaseConnection();
                    return response()->json(['erro' => '404',
                    'message' => 'Nenhum Dashboard encontrado.',
                                        ], 404);
                }
                else{
                    $this->rolbackDatabaseConnection();
                    return DashboardvendasResource::collection($dashboard);    
                }
            }

        } catch (ModelNotFoundException $e) {
            $this->rolbackDatabaseConnection();
            return response()->json(['erro' => '404',
            'message' => 'Dashboard nao encontrado.',
            ], 404);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    /*public function edit(Dashboardvendas $dashboardvendas)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    /*public function update(Request $request, Dashboardvendas $dashboardvendas)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    /*public function destroy(Dashboardvendas $dashboardvendas)
    {
        //
    }*/
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
