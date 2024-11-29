<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PedidoitemResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'idpedido'=> $this->idpedido,
            'numitem'=> $this->numitem,
            'idproduto'=> $this->idproduto,
            'nomeproduto' => optional($this->produto)->nome,//new ProdutoResource($this->whenLoaded('produto')),
            'codpreco'=> $this->codpreco,
            'quantidade'=> $this->quantidade,
            'vrunit'=> $this->vrunit,
            'vrtotal'=> $this->vrtotal,
            'codbarras'=> $this->codbarras,
            'percdesc'=> $this->percdesc,
            'vrdesc'=> $this->vrdesc,
            'percacres'=> $this->percacres,
            'vracres'=> $this->vracres,
            'perccomis'=> $this->perccomis,
            'vrcomis'=> $this->vrcomis,
            'unidade'=> $this->unidade,
            'percdescunit'=> $this->percdescunit,
            'vrdescunit'=> $this->vrdescunit,
            'vrunitoriginal'=> $this->vrunitoriginal,
            'percacresunit'=> $this->percacresunit,
            'vracresunit'=> $this->vracresunit
        ];
    }
}
