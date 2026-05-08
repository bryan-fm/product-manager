<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $id = $this->route('product')->id;

        return [
            'name' => "required|string|max:255|unique:products,name,$id",
            'description' => 'nullable|string|max:255',
            'price' => 'required|numeric|min:0.01',
            'stock' => 'required|integer|min:0',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'O nome é obrigatório.',
            'name.string' => 'O nome deve ser uma string.',
            'name.max' => 'O nome não pode exceder 255 caracteres.',
            'name.unique' => 'Já existe um produto com esse nome.',
            'description.string' => 'A descrição deve ser uma string.',
            'description.max' => 'A descrição não pode exceder 255 caracteres.',
            'price.required' => 'O preço é obrigatório.',
            'price.numeric' => 'O preço deve ser um número.',
            'price.min' => 'O preço deve ser maior que zero.',
            'stock.required' => 'O estoque é obrigatório.',
            'stock.integer' => 'O estoque deve ser um número inteiro.',
            'stock.min' => 'O estoque não pode ser negativo.',
        ];
    }
}