<?php

namespace App\Http\Controllers\Api;

use App\Models\Produtoimagem;
use App\Models\Banco;
use App\Http\Controllers\Controller;
use App\Http\Resources\ProdutoimagemResource;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class ProdutoimagemController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    /*public function create()
    {
        
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
            $imgs = $request->input('data');
            foreach($imgs as $img){
                $image_file= base64_decode($img['base64Image']);
                //return $image_file;
                //$extension = $image_file->extension();
                $imageName = $img['nome'].'.png';

                $directoryPath = public_path($aux.'\\produtos\\imagens\\'.$img['codprod'].'\\');
            
                if (!File::exists($directoryPath)) {
                    File::makeDirectory($directoryPath, 0777, true);
                }

                $imagePath = public_path($aux.'\\produtos\\imagens\\'.$img['codprod'].'\\'.$imageName);
                file_put_contents($imagePath, $image_file);
                //$image_file->move(public_path('img/produtos'));
                $img['nome'] = $imageName;
                $img['diretorio'] = $imagePath;

                $imagem = Produtoimagem::create($img);
            } 
            $this->rolbackDatabaseConnection();
            return $request;                      
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, $id)
    {
        try {
            $responseimages = [];
            $aux = $this->changeDatabaseConnection($request);
            if(!$aux){
                return response()->json(['erro' => '404',
                'message' => 'Token Invalido.',
                                ], 404);   
            }
            else{
                $imagens = Produtoimagem::where('codprod', $id)->get();
                /*return response()->json([
                    'data' => $imagens], 200);*/
                if($imagens!='[]'){
                    foreach($imagens as $imagem){
                        $image_64= base64_encode(file_get_contents($imagem['diretorio']));
                        $auxiliar = ['nome'=>$imagem['nome'],
                                    'base64'=>$image_64];
                        array_push($responseimages, $auxiliar);
                    }
                }               
                else{
                    $this->rolbackDatabaseConnection();
                    return response()->json(['erro' => '404',
                    'message' => 'Imagem nao encontrada.',
                                        ], 404);
                }
                $this->rolbackDatabaseConnection();

                return response()->json([
                                'data' => $responseimages], 200);
            }

        } catch (ModelNotFoundException $e) {
            $this->rolbackDatabaseConnection();
            return response()->json(['erro' => '404',
            'message' => 'Imagem nao encontrada.',
            ], 404);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    /*public function edit(Produtoimagem $produtoimagem)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    /*public function update(Request $request, Produtoimagem $produtoimagem)
    {
        //
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
            $nome = $request->query('nome');
            if($nome!=null){
                $imagens = Produtoimagem::where('codprod', $id)->where('nome', $nome)->get();//DB::table('produtos_imagens')->where('codprod', $id);
                if (!$imagens) {
                    return response()->json(['erro' => '404',
                    'message' => 'Imagens nao encontradas.',
                                        ], 404);
                }else{
                    foreach($imagens as $imagem){
                        if (file_exists(str_replace('\\', '/', $imagem['diretorio']))) {
                            unlink($imagem['diretorio']);
                        }
                    }
                    DB::table('produtos_imagens')->where('codprod', $id)->where('nome', $nome)->delete();
                }
            }else{
                $imagens = Produtoimagem::where('codprod', $id)->get();//DB::table('produtos_imagens')->where('codprod', $id);
                if (!$imagens) {
                    return response()->json(['erro' => '404',
                    'message' => 'Imagens nao encontradas.',
                                        ], 404);
                }else{
                    foreach($imagens as $imagem){
                        if (file_exists(str_replace('\\', '/', $imagem['diretorio']))) {
                            unlink($imagem['diretorio']);
                        }
                    }
                    DB::table('produtos_imagens')->where('codprod', $id)->delete();
                }
            }
            $this->rolbackDatabaseConnection();
            return response()->json(['erro' => '204',
                'message' => 'Imagens excluidas.',
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
