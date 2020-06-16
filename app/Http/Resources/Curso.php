<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\TipoCurso as TipoCurso;

class Curso extends JsonResource {
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request) {
        $tipo = TipoCurso::find($this->tipo);

        return [
            'id' => $this->id,
            'abreviatura' => $this->abreviatura,
            'nome' => $this->nome,
            'tipo' => ($tipo==null?"-----":$tipo->nome),
            'semestres' => $this->semestres,
            'ECTS' => $this->ECTS,
            'vagas' => $this->vagas,
            'contato' => $this->contato,
            'objetivos' => ($this->objetivos)?$this->objetivos:'',
            'fotografia' => $this->fotografia,
        ];
    }
}
