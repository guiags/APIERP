<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProdutoRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'codprod'=> ['required'], 
            'nome'=> ['required', 'max:80'], 
            'descrcompleta'=> ['max:1000'], 
            'referencia'=> ['required', 'max:30'], 
            'codgrupo'=> ['required'], 
            'unidade'=> ['required', 'max:6'], 
            'codbarras'=> ['max:14'], 
            'preco'=> ['required'], 
            'fotoprod'=> ['required', 'max:200'], 
            'usagrade'=> ['required', 'max:1'], 
            'estoque'=> ['required'], 
            'usalote'=> ['required', 'max:1'], 
            'inativo'=> ['required', 'max:1']
        ];
    }
}
