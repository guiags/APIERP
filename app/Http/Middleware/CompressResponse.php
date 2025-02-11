<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use ZipArchive;

class CompressResponse
{
        /**
         * Handle an incoming request.
         *
         * @param  \Illuminate\Http\Request  $request
         * @param  \Closure  $next
         * @return mixed
         */
        public function handle(Request $request, Closure $next)
        {
            $response = $next($request);
    
            // Verifica se o cabeçalho 'Accept-Encoding' contém 'gzip' ou 'deflate'
            /*if (strpos($request->header('Accept-Encoding'), 'gzip') !== false) {
                // Aplica a compressão Gzip
                $response->headers->set('Content-Encoding', 'gzip');
                $response->setContent(base64_encode(gzencode($response->getContent())));
            }
            

            return $response;*/
            
            /*$zip = new ZipArchive;
            $zipName = 'produtos.zip';
            
            // Gerar arquivo zip diretamente na memória
            $tempFile = tempnam(sys_get_temp_dir(), $zipName);
            $zip->open($tempFile, ZipArchive::CREATE | ZipArchive::OVERWRITE);
            
            // Adicionar a string como um arquivo dentro do zip
            $zip->addFromString('produtos.json', $response->getContent());
            $zip->close();
        
            // Retornar o arquivo zip como resposta
            return response()->streamDownload(function () use ($tempFile) {
                readfile($tempFile);
                unlink($tempFile); // Remover arquivo temporário após o download
            }, $zipName);*/
            
            $response = $next($request);

            $zipName = 'produtos.zip';
            $tempDir = storage_path('app/temp');
            $tempPath = "$tempDir/$zipName";
            $jsonFile = "$tempDir/produtos.json";
        
            // Criar diretório temporário
            if (!file_exists($tempDir)) {
                mkdir($tempDir, 0777, true);
            }
        
            // Salvar JSON temporário
            file_put_contents($jsonFile, $response->getContent());
        
            // Criar ZIP via comando do sistema
            shell_exec("zip -j $tempPath $jsonFile");
        
            // Remover JSON temporário
            unlink($jsonFile);
            
            //return ini_get('disable_functions');
            
            return response()->download($tempPath)->deleteFileAfterSend(true);
        }
}
