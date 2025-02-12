<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ConfigvendedorResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'idvendedor' => $this->idvendedor,
            'exibeclibloq' => $this->exibeclibloq,
            'listaprodvazia' => $this->listaprodvazia,
            'naoexibeimgprod' => $this->naoexibeimgprod,
            'sincronizacao' => $this->sincronizacao,
            'dt_ult_sinc' => $this->dt_ult_sinc
        ];
    }
}
