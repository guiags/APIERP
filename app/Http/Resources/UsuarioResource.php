<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UsuarioResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [ //parent::toArray($request);
            'id' => $this->id,
            'usuario' => $this->usuario,
            'senha' => $this->senha,
            'idvendedor' => $this->idvendedor, 
            'nomevendedor' => $this->nomevendedor,
            'perccomiss' => $this->perccomiss,
            'percdescmax' => $this->percdescmax,
            'comisregrpercdesc' => $this->comisregrpercdesc,
            'comisregrperccomis' => $this->comisregrperccomis
        ];    
    }
}
