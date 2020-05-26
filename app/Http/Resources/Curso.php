<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Curso extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'abreviatura' => $this->abreviatura,
            'nome' => $this->nome,
            'tipo' => $this->tipo,
            'semestres' => $this->semestres,
            'ECTS' => $this->ECTS,
            'vagas' => $this->vagas,
            'contato' => $this->contato,
            'objetivos' => $this->objetivos,
        ];
    }
}
