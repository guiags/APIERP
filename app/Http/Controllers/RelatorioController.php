<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Barryvdh\DomPDF\Facade;
use PDF;
use Illuminate\Http\Request;

class RelatorioController extends Controller
{
    public function gerarRelatorio(Request $request)
    {
        // Receber o JSON enviado pela requisição
        $dados = $request->json()->all();

        // Preparar os dados para exibição no PDF
        // Aqui você pode manipular os dados conforme a necessidade
        $viewData = [
            'dados' => $dados, // Passa os dados recebidos para a view
        ];
        
        // Gerar o PDF a partir de uma view
        $pdf = PDF::loadView('relatorios.relatorio_pdf', $viewData);

        // Retornar o PDF gerado como resposta
        return $pdf->download('relatorio.pdf');
    }
}
