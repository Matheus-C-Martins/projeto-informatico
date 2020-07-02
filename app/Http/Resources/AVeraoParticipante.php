<?php

namespace App\Http\Resources;

use App\Participante;
use App\AtividadeAVerao as Atividade;
use Illuminate\Http\Resources\Json\JsonResource;
use Laravel\Scout\Searchable;

class AVeraoParticipante extends JsonResource {
    use Searchable;
    public function toArray($request) {
        $participante = Participante::find($this->participante);
        $atividade = Atividade::find($this->atividade);

        $t = strtotime($this->data);
        $data = date("Y-m-d", $t);
        $hora = date("H:i:s", $t);
        
        return [
            'id' => $this->id,
            'atividade' => $atividade->nome,
            'participante' => $participante->nome,
            'data'=> $data,
            'hora'=> $hora,
        ];
    }

    public function toSearchableArray() {
       return [
          'id' => $this->id
       ];
    }
}