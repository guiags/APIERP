<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PedidoResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'=> $this->id, 
            'emissao'=> $this->emissao,
            'entrega'=>$this->entrega, 
            'tipo'=> $this->tipo, 
            'status'=> $this->status, 
            'idcliente'=> $this->idcliente,
            'nomepessoa'=> optional($this->cliente)->nomepessoa,
            'cpfcnpj'=> optional($this->cliente)->cpfcnpj,
            'clientenovo'=> optional($this->cliente)->novo,
            //'nomepessoa'=> $this->cliente,
            'percdesc'=> (float) $this->percdesc, 
            'vrdesc'=> (float) $this->vrdesc, 
            'vrbruto'=> (float) $this->vrbruto, 
            'vrliquido'=> (float) $this->vrliquido, 
            'obs'=> $this->obs, 
            'idformapag1'=> $this->idformapag1, 
            'idformapag2'=> $this->idformapag2, 
            'idplanopag1'=> $this->idplanopag1, 
            'idplanopag2'=> $this->idplanopag2, 
            'vrpago1'=> (float) $this->vrpago1, 
            'vrpago2'=> (float) $this->vrpago2, 
            'idvendedor'=> $this->idvendedor, 
            'vrcomis'=> (float) $this->vrcomis, 
            'perccomis'=> (float) $this->perccomis,
            'itens' =>// $this->itens
            PedidoitemResource::collection($this->whenLoaded('itens'))

            //'nomeproduto' => $this->itens->nome
        ];
    }
}
