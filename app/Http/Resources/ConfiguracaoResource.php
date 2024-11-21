<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ConfiguracaoResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'usacomisregresdesc' => $this->usacomisregresdesc,
            'utilizaindicepreco' => $this->utilizaindicepreco,
            'validaestneg' => $this->validaestneg,
            'bloqestneg' => $this->bloqestneg,
            'bloqclidebvenc' => $this->bloqclidebvenc,
            'bloqclilimitediasvenc' => $this->bloqclilimitediasvenc,
            'bloqclidiasvencatecor' => $this->bloqclidiasvencatecor,
            'bloqclidiasvencaposcor' => $this->bloqclidiasvencaposcor,
            'precocasasdecimais' => $this->precocasasdecimais,
            'adicobsclinoped' => $this->adicobsclinoped,
            'carregalistaprodvazia' => $this->carregalistaprodvazia,
        ];
    }
}
