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

class LotesController extends Controller
{
    public function loteProdutos(Request $request)
    {
    $error_count = 0;
    $replaces = array("`renovar_teste`.", "`");
    $aux = $this->changeDatabaseConnection($request);
        if (!$aux){
            return response()->json(['erro' => '404',
            'message' => 'Token Invalido.',
                            ], 404);
        }
        else{
            //$produtos = $request->input('data');
            //$data = json_decode($produtos, true);
            $auxiliar = 0;
            $responsecodssuc=[];
            $responsecodsermes=[];

            //foreach ($request as $item) {
                //if (isset($item['update'])) {
                    $produtos_update = $request->input('update'); // Obtém o array de updates
                    $update = [];
                    $update_er = [];
                    foreach($produtos_update as $produto){
                        $auxiliar = $produto['codprod'];
                        
                        ///return $produto;
                        Try{
                            if (!DB::table('produtos')->where('codprod', $auxiliar)->first()) {
                                array_push($update_er, [$auxiliar => '404 - Produto nao encontrado']);
                            }else{ 
                                DB::table('produtos')
                                ->where('codprod', $auxiliar)
                                ->update(collect($produto)->only(['codprod', 'nome', 'descrcompleta', 'referencia', 'codgrupo', 'unidade', 'codbarras', 'preco', 'fotoprod', 'usagrade', 'estoque', 'usalote', 'inativo', 'dtmodificacao'])
                                ->toArray());
                                array_push($update, $auxiliar);
                            }
                        }catch (\Exception $e) {
                            //return $this->traduzir("400 - Incorrect decimal value: 'fasdf' for column `renovar_teste`.`produtos`.`estoque` at row 1");
                            array_push($update_er, [$auxiliar => '400 - ' . $this->traduzir(str_replace($replaces,"",$e->errorInfo[2]))]);
                            $error_count++; 
                            ///return $e;  
                        }
                    }
                    array_push($responsecodssuc, ['update: ' => $update]);
                    array_push($responsecodsermes, ['update: ' => $update_er]);
                //}
                //else if (isset($item['delete'])) {
                    $produtos_delete = $request->input('delete'); // Obtém o array de updates
                    $delete = [];
                    $delete_er = [];
                    foreach($produtos_delete as $produto){
                        $auxiliar = $produto;
                        //return $produto;
                        Try{
                            if (!DB::table('produtos')->where('codprod', $auxiliar)->first()) {
                                array_push($delete_er, [$auxiliar => '404 - Produto nao encontrado']);
                            }else{ 
                                DB::table('produtos_precos')->where('codprod', $auxiliar)->delete();
                                DB::table('produtos_lotes')->where('codprod', $auxiliar)->delete();
                                DB::table('produtos_grades')->where('codprod', $auxiliar)->delete();
                                DB::table('produtos')->where('codprod', $auxiliar)->delete();
                                array_push($delete, $auxiliar);
                            }
                        }catch (\Exception $e) {
                            array_push($delete_er, [$auxiliar => '400 - ' . $this->traduzir(str_replace($replaces,"",$e->errorInfo[2]))]);
                            $error_count++; 
                        }
                    }
                    array_push($responsecodssuc, ['delete: ' => $delete]);
                    array_push($responsecodsermes, ['delete: ' => $delete_er]);
                }
                //else if (isset($item['create'])) {
                    $produtos_create = $request->input('create'); // Obtém o array de updates
                    $create = [];
                    $create_er = [];
                    foreach($produtos_create as $produto){
                        $auxiliar = $produto;
                        Try{
                            $precos = $produto['precos'];
                            foreach($precos as $preco){
                                Produtopreco::create($preco);
                            }
                            $lotes = $produto['lotes'];
                            foreach($lotes as $lote){
                                Produtolote::create($lote);
                            }
                            $grades = $produto['grades'];
                            foreach($grades as $grade){
                                Produtograde::create($grade);
                            }
                            $prod = Produto::create($produto);
                            array_push($create, $produto['codprod']);
                        }catch (\Exception $e) {
                            array_push($create_er, [$produto['codprod'] => '400 - ' . $this->traduzir(str_replace($replaces,"",$e->errorInfo[2]))]);
                            $error_count++; 
                        }
                    }
                    array_push($responsecodssuc, ['create: ' => $create]);
                    array_push($responsecodsermes, ['create: ' => $create_er]);                          
                //}
            //}
            if ($error_count==0){
                return response()->json([
                    'sucesso' => $responsecodssuc], 200);        
            }
            return response()->json([
                'erro' => $responsecodsermes,
                    'sucesso' => $responsecodssuc], 200); 
        
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


    function traduzir($texto, $idiomaDestino = 'pt') {
        $url = "https://translate.googleapis.com/translate_a/single?client=gtx&sl=auto&tl={$idiomaDestino}&dt=t&q=" . urlencode($texto);
    
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    
        $resposta = curl_exec($ch);
        curl_close($ch);
    
        $traducao = json_decode($resposta, true);
        return $traducao[0][0][0] ?? 'Erro na tradução';
    }
}
