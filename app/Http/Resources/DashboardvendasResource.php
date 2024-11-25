<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DashboardvendasResource extends JsonResource
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
            'mes' => $this->mes, 
            'ano' => $this->ano, 
            'valor' => $this->valor
        ];
    }
}
