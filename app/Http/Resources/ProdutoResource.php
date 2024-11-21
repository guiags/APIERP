<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProdutoResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'codprod' => $this->codprod,
            'nome' => $this->nome,
            'descrcompleta' => $this->descrcompleta,
            'referencia' => $this->referencia,
            'codgrupo' => $this->codgrupo,
            'unidade' => $this->unidade,
            'codbarras' => $this->codbarras,
            'preco' => $this->preco,
            'fotoprod' => $this->fotoprod,
            'usagrade' => $this->usagrade,
            'estoque' => $this->estoque,
            'usalote' => $this->usalote,
            'inativo' => $this->inativo,
            'precos' => $this->precos,
            'lotes' => $this->lotes
        ];
    }
}
