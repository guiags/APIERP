<?php

namespace App\Http\Controllers\Api;

use App\Models\Usuario;
use App\Models\Banco;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\StoreUsuarioRequest;
use App\Http\Resources\UsuarioResource;
use Illuminate\Foundation\Http\FormRequest;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;

class UsuarioController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $aux = $this->changeDatabaseConnection($request);
        if (!$aux){
            return response('Token Inválido', 404);
        }
        else{
            $usuario = Usuario::all();
            $this->rolbackDatabaseConnection();
            return UsuarioResource::collection($usuario);    
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
            return response('Token Inválido', 404);
        }
        else{
            $usuario = Usuario::create($request->all());
            $this->rolbackDatabaseConnection();
            return new UsuarioResource($usuario);
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
                return response('Token Inválido', 404);    
            }
            else{
                $usuario = Usuario::findOrFail($id);
                if(!$usuario){
                    response('Usuário não encontrado!', 404);
                }
                else{
                    $this->rolbackDatabaseConnection();
                    return new UsuarioResource($usuario);
                }
            }

        } catch (ModelNotFoundException $e) {
            $this->rolbackDatabaseConnection();
            return response()->json([
                'message' => 'Usuário não encontrado.'
            ], 404);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    /*public function edit(Usuario $usuario)
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
            return response('Token Inválido', 404);
        }
        else{
            $usuario = DB::table('usuarios')->where('id', $id)->first();
            if (!$usuario) {
                return response('Usuário não encontrado', 404);
            } 
            DB::table('usuarios')
            ->where('id', $id)
            ->update($request->only(['usuario','senha','idvendedor', 'nomevendedor','perccomiss','percdescmax','comisregrpercdesc','comisregrperccomis']));   

            $this->rolbackDatabaseConnection();
            return new UsuarioResource($usuario);
        }

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id, Request $request)
    {
        $aux = $this->changeDatabaseConnection($request);
        if(!$aux){
            return response('Token Inválido', 404);
        }
        else{
            $usuario = DB::table('usuarios')->where('id', $id)->first();
            if (!$usuario) {
                return response('Usuário não encontrado', 404);
            } 
            DB::table('usuarios')->where('id', $id)->delete();
            $this->rolbackDatabaseConnection();
            return response('Erro ao excluir', 204);
        }
    }

    public function showByIdVendedor($idvendedor, Request $request)
    {
        $aux = $this->changeDatabaseConnection($request);
        if(!$aux){
            return response()->json(['codigo' => '404',
            'message' => 'Token Invalido.',
                                ], 404);
        }
        $usuario = Usuario::where('idvendedor', $idvendedor)->first();
        $this->rolbackDatabaseConnection();
        if ($usuario) {
            return response()->json($usuario); 
        }

        return response()->json(['codigo' => '404',
            'message' => 'Id de Vendedor nao encontrados.',
                                ], 404);
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
           // Config::set('database.connections.mysql.username', $UserBanco);
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
