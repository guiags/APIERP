<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProdutogradeResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'codgrade' => $this->codgrade,
            'nomegrade' => $this->nomegrade,
            'coditgrade' => $this->coditgrade, 
            'nomeitgrade' => $this->nomeitgrade, 
            'codgradepai' => $this->codgradepai, 
            'nomegradepai' => $this->nomegradepai, 
            'coditgradepai' => $this->coditgradepai, 
            'nomeitgradepai' => $this->nomeitgradepai, 
            'preco' => round((float)$this->preco, 4), 
            'codbarras' => $this->codbarras
        ];
    }
}
