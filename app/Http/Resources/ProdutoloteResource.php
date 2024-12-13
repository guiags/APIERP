<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProdutoloteResource extends JsonResource
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
            'numlote' => $this->numlote,
            'datafab' => $this->datafab,
            'dataval' => $this->dataval,
            'estoque' => round((float)$this->estoque, 4)
        ];
    }
}
