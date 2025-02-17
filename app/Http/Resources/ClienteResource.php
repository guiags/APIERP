<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ClienteResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return  [ //parent::toArray($request);
            'codpessoa' => $this->codpessoa, 
            'nomepessoa' => $this->nomepessoa, 
            'tipopessoa' => $this->tipopessoa, 
            'cpfcnpj'  => $this->cpfcnpj, 
            'inscestadual' => $this->inscestadual, 
            'email' => $this->email, 
            'telefone1' => $this->telefone1, 
            'telefone2' => $this->telefone2, 
            'celular1' => $this->celular1, 
            'celular2' => $this->celular2, 
            'logradouro' => $this->logradouro, 
            'numero' => $this->numero, 
            'complemento' => $this->complemento, 
            'bairro' => $this->bairro, 
            'cidade' => $this->cidade, 
            'uf' => $this->uf, 
            'cep' => $this->cep, 
            'obs' => $this->obs, 
            'datadocvenc' => $this->datadocvenc, 
            'bloqueado' => $this->bloqueado, 
            'obsbloq' => $this->obsbloq, 
            'idvendedor' => $this->idvendedor, 
            'novo' => $this->novo,
            'dtmodificacao' => $this->dtmodificacao

        ];    
    }
}
