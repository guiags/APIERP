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
            'tipo'=> $this->tipo, 
            'status'=> $this->status, 
            'idcliente'=> $this->idcliente,
            'nomepessoa'=> optional($this->cliente)->nomepessoa,
            'cpfcnpj'=> optional($this->cliente)->cpfcnpj,
            'clientenovo'=> optional($this->cliente)->novo,
            'entrega'=> $this->entrega, 
            'percdesc'=> $this->percdesc, 
            'vrdesc'=> $this->vrdesc, 
            'vrbruto'=> $this->vrbruto, 
            'vrliquido'=> $this->vrliquido, 
            'obs'=> $this->obs, 
            'idformapag1'=> $this->idformapag1, 
            'idformapag2'=> $this->idformapag2, 
            'idplanopag1'=> $this->idplanopag1, 
            'idplanopag2'=> $this->idplanopag2, 
            'vrpago1'=> $this->vrpago1, 
            'vrpago2'=> $this->vrpago2, 
            'idvendedor'=> $this->idvendedor, 
            'vrcomis'=> $this->vrcomis, 
            'perccomis'=> $this->perccomis,
            'itens' =>// $this->itens
            PedidoitemResource::collection($this->whenLoaded('itens'))

            //'nomeproduto' => $this->itens->nome
        ];
    }
}
