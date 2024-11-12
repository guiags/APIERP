<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreClienteRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'codpessoa' => ['required'], 
            'nomepessoa' => ['required', 'max:80'], 
            'tipopessoa' => ['required', 'max:1'], 
            'cpfcnpj'  => ['required', 'max:14'], 
            'inscestadual' => ['max:20'], 
            'email' => ['required'], 
            'telefone1' => ['max:10'], 
            'telefone2' => ['max:10'], 
            'celular1' => ['max:11'], 
            'celular2' => ['max:11'], 
            'logradouro' => ['max:100'], 
            'numero' => ['required'], 
            'complemento' => ['max:50'], 
            'bairro' => ['max:80'], 
            'cidade' => ['max:80'], 
            'uf' => ['max:2'], 
            'cep' => ['max:8'], 
            'obs' => ['required'], 
            'datadocvenc' => ['required'], 
            'bloqueado' => ['max:1'], 
            'obsbloq'=> ['required'], 
            'idvendedor'=> ['required'], 
            'novo' => ['max:1']
        ];
    }
}
