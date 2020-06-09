<?php

namespace App\Http\Resources;

use App\Contacto;
use App\Escola;
use Illuminate\Http\Resources\Json\JsonResource;
use Laravel\Scout\Searchable;

class Atividade extends JsonResource {
    use Searchable;
    public function toArray($request) {
        $contacto = Contacto::find($this->contacto);
        $escola = Escola::find($this->escola);

        $t = strtotime($this->data);
        $data = date("Y-m-d", $t);
        $hora = date("H:i:s", $t);

        $t_complete = '-----';
        switch($this->tipo_de_atividade) {
            case 'diaESTG':
                $t_complete = 'Dia ESTG';
                break;
            case 'workshop':
                $t_complete = 'Workshop';
                break;
        }
        
        return [
            'id' => $this->id,
            'escola' => $escola->nome,
            'turma' => (!$this->turma?"-----":$this->turma),
            'ano' => (!$this->ano?"-----":$this->ano),
            'numero_alunos' => (!$this->numero_de_alunos?"-----":$this->numero_de_alunos),
            'data'=> $data,
            'hora'=> $hora,
            'duracao' => (!$this->duracao?"-----":$this->duracao),
            'email' => (!$contacto->email?"-----":$contacto->email),
            'telefone' => (!$contacto->telefone?"-----":$contacto->telefone),
            'nome' => (!$contacto->nome?"-----":$contacto->nome),
            'descricao' => ($this->descricao)?$this->descricao:'',
            'tipo_atividade' => $t_complete,
        ];
    }

    public function toSearchableArray() {
       return [
          'id' => $this->id
       ];
    }
}