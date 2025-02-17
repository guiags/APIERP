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
            'preco' => round((float)$this->preco, 4),
            'fotoprod' => $this->fotoprod,
            'usagrade' => $this->usagrade,
            'estoque' => round((float)$this->estoque, 4),
            'usalote' => $this->usalote,
            'inativo' => $this->inativo,
            //'precos' => $this->precos,
            'precos' => ProdutoprecoResource::collection($this->whenLoaded('precos')),
            //'lotes' => $this->lotes,
            'lotes' => ProdutoloteResource::collection($this->whenLoaded('lotes')),
            //'grades' => $this->grades
            'grades' => ProdutogradeResource::collection($this->whenLoaded('grades')),
            'dtmodificacao' => $this->dtmodificacao
        ];
    }
}
