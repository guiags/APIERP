<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreConfiguracaoRequest extends FormRequest
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
            'id'=> ['required'],
            'usacomisregresdesc'=> ['required', 'max:1'], 
            'utilizaindicepreco'=> ['required', 'max:1'], 
            'validaestneg'=> ['required', 'max:1'], 
            'bloqestneg'=> ['required', 'max:1'], 
            'bloqclidebvenc'=> ['required', 'max:1'], 
            'bloqclilimitediasvenc'=> ['required'], 
            'bloqclidiasvencatecor'=> ['required', 'max:20'], 
            'bloqclidiasvencaposcor'=> ['required', 'max:20'], 
            'precocasasdecimais'=> ['required'], 
            'adicobsclinoped'=> ['required', 'max:1'], 
            'carregalistaprodvazia'=> ['required', 'max:1']
        ];
    }
}
