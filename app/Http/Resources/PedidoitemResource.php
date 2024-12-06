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
            //'lotes' => optional($this->produto)->lotes,
            'codpreco'=> $this->codpreco,
            'quantidade'=> (float) $this->quantidade,
            'vrunit'=> (float) $this->vrunit,
            'vrtotal'=> (float) $this->vrtotal,
            'codbarras'=> $this->codbarras,
            'percdesc'=> (float) $this->percdesc,
            'vrdesc'=> (float) $this->vrdesc,
            'percacres'=> (float) $this->percacres,
            'vracres'=> (float) $this->vracres,
            'perccomis'=> (float) $this->perccomis,
            'vrcomis'=> (float) $this->vrcomis,
            'unidade'=> $this->unidade,
            'percdescunit'=> (float) $this->percdescunit,
            'vrdescunit'=> (float) $this->vrdescunit,
            'vrunitoriginal'=> (float) $this->vrunitoriginal,
            'percacresunit'=> (float) $this->percacresunit,
            'vracresunit'=> (float) $this->vracresunit,
            'grades'=> $this->grade
        ];
    }
}
