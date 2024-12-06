<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CtreceberResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'coddoc' => $this->coddoc, 
            'numdoc' => $this->numdoc, 
            'dataemis' => $this->dataemis, 
            'datavenc' => $this->datavenc, 
            'codcli' => $this->codcli, 
            'nomecli' => $this->nomecli, 
            'vrdoc' => (float) $this->vrdoc, 
            'conta' => $this->conta, 
            'planopag' => $this->planopag, 
            'datapago' => $this->datapago, 
            'vrpago' => (float) $this->vrpago, 
            'situacao' => $this->situacao
        ];
    }
}
