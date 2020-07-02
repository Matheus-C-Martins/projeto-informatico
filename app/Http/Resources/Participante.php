<?php

namespace App\Http\Resources;

use App\AcademiaDeVerao as Academia;
use App\Escola;
use Illuminate\Http\Resources\Json\JsonResource;
use Laravel\Scout\Searchable;

class Participante extends JsonResource {
    use Searchable;
    public function toArray($request) {
        $academia = Academia::find($this->academia_de_verao);
        $escola = Escola::find($this->escola);

        $t = strtotime($academia->data);
        $data = date("Y", $t);

        $t = strtotime($this->data_de_nascimento);
        $dataNascimento = date("Y-m-d", $t);
        
        return [
            'id' => $this->id,
            'nome' =>(!$this->nome?"-----":$this->nome),
            'escola' => $escola->nome,
            'ano' =>(!$this->ano?"-----":$this->ano),
            'curso' =>(!$this->curso?"-----":$this->curso),
            'data_de_nascimento' =>$dataNascimento,
            'contacto_do_aluno' =>(!$this->contacto_do_aluno?"-----":$this->contacto_do_aluno),
            'contacto_do_ee' =>(!$this->contacto_do_ee?"-----":$this->contacto_do_ee),
            'encarregado_de_educacao' =>(!$this->encarregado_de_educacao?"-----":$this->encarregado_de_educacao),
            'regiao' =>(!$this->regiao?"-----":$this->regiao),
            'academia_de_verao' => $data,
        ];
    }

    public function toSearchableArray() {
       return [
          'id' => $this->id
       ];
    }
}