<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class HistpedidoResource extends JsonResource
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
            'status'=> $this->status, 
            'idcliente'=> $this->idcliente, 
            'entrega'=> $this->entrega, 
            'percdesc'=> round((float)$this->percdesc, 4), 
            'vrdesc'=> round((float)$this->vrdesc, 4), 
            'vrbruto'=> round((float)$this->vrbruto, 4), 
            'vrliquido'=> round((float)$this->vrliquido, 4), 
            'obs'=> $this->obs, 
            'idformapag1'=> $this->idformapag1, 
            'idformapag2'=> $this->idformapag2, 
            'idplanopag1'=> $this->idplanopag1, 
            'idplanopag2'=> $this->idplanopag2, 
            'vrpago1'=> round((float)$this->vrpago1, 4), 
            'vrpago2'=> round((float)$this->vrpago2, 4), 
            'idvendedor'=> $this->idvendedor, 
            'vrcomis'=> round((float)$this->vrcomis, 4), 
            'perccomis'=> round((float)$this->perccomis, 4),
            'itens' => $this->itens
        ];
    }
}
