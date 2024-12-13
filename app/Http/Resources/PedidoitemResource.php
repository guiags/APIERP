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
            'quantidade'=> round((float)$this->quantidade, 4),
            'vrunit'=> round((float)$this->vrunit, 4),
            'vrtotal'=> round((float)$this->vrtotal, 4),
            'codbarras'=> $this->codbarras,
            'percdesc'=> round((float)$this->percdesc, 4),
            'vrdesc'=> round((float)$this->vrdesc, 4),
            'percacres'=> round((float)$this->percacres, 4),
            'vracres'=> round((float)$this->vracres, 4),
            'perccomis'=> round((float)$this->perccomis, 4),
            'vrcomis'=> round((float)$this->vrcomis, 4),
            'unidade'=> $this->unidade,
            'percdescunit'=> round((float)$this->percdescunit, 4),
            'vrdescunit'=> round((float)$this->vrdescunit, 4),
            'vrunitoriginal'=> round((float)$this->vrunitoriginal, 4),
            'percacresunit'=> round((float)$this->percacresunit, 4),
            'vracresunit'=> round((float)$this->vracresunit, 4),
            'grades'=> $this->grade
        ];
    }
}
