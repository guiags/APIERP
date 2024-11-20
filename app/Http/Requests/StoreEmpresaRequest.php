<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreEmpresaRequest extends FormRequest
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
            'id' => ['required'],
            'nomefantasia' => ['required', 'max:80'],
            'endereco' => ['required', 'max:120'],
            'bairro' => ['required', 'max:80'],
            'cidade' => ['required', 'max:80'],
            'uf' => ['required', 'max:2'],
            'telefone' => ['required', 'max:15'],
            'celular' => ['required', 'max:15'],
            'cnpj' => ['required', 'max:18']
        ];
    }
}
