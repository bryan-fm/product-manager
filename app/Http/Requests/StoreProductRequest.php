<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255|unique:products,name',
            'description' => 'nullable|string|max:255',
            'price' => 'required|numeric|min:0.01',
            'stock' => 'required|integer|min:0',
        ];
    }

    public function messages(): array
    {
        return [
            'price.min' => 'O preço deve ser maior que zero.',
            'price.numeric' => 'O preço deve ser um número.',
            'price.required' => 'O preço é obrigatório.',
            'stock.min' => 'O estoque não pode ser negativo.',
            'stock.integer' => 'O estoque deve ser um número inteiro.',
            'stock.required' => 'O estoque é obrigatório.',
            'name.required' => 'O nome é obrigatório.',
            'name.string' => 'O nome deve ser uma string.',
            'name.max' => 'O nome não pode exceder 255 caracteres.',
            'name.unique' => 'Já existe um produto com esse nome.',
            'description.string' => 'A descrição deve ser uma string.',
            'description.max' => 'A descrição não pode exceder 255 caracteres.',
        ];
    }
}