<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreConfigvendedorRequest extends FormRequest
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
            'idvendedor'=> ['required'],
            'exibeclibloq'=> ['max:1'], 
            'listaprodvazia'=> ['max:1'], 
            'naoexibeimgprod'=> ['max:1'], 
            'sincronizacao'=> ['max:2'] 
        ];
    }
}
