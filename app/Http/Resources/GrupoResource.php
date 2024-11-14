<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class GrupoResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'codgrupo' => $this->codgrupo,
            'descricao' => $this->descricao,
            'dir_imagem' => $this->dir_imagem
        ];
    }
}
