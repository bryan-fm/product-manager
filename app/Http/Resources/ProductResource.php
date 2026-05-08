<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        // Aqui você escolhe EXATAMENTE o que quer que apareça na API
        return [
            'id'          => $this->id,
            'nome'        => $this->name,
            'descricao'   => $this->description,
            'preco'       => $this->price,
            'estoque'     => $this->stock,
            // Exemplo de formatação:
            'data_criacao' => $this->created_at->format('d/m/Y H:i'),
        ];
    }

    public function paginationInformation($request, $paginated, $default): array
    {
        // Aqui removemos o array 'links' (com labels e active) 
        // e deixamos apenas o essencial para navegação técnica.
        return [
            'meta' => [
                'current_page' => $paginated['current_page'],
                'last_page'    => $paginated['last_page'],
                'per_page'     => $paginated['per_page'],
                'total'        => $paginated['total'],
            ],
            'links' => [
                'first' => $paginated['first_page_url'],
                'last'  => $paginated['last_page_url'],
                'prev'  => $paginated['prev_page_url'],
                'next'  => $paginated['next_page_url'],
            ],
        ];
    }
}
