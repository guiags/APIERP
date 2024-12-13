<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProdutoprecoResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
           'codpreco' => $this->codpreco,
           'preco' =>round((float)$this->preco, 4), 
           'descricao' => $this->descricao    
        ];
    }
}
