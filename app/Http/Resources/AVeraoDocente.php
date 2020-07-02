<?php

namespace App\Http\Resources;

use App\Docente;
use App\AtividadeAVerao as Atividade;
use App\Sala;
use Illuminate\Http\Resources\Json\JsonResource;
use Laravel\Scout\Searchable;

class AVeraoDocente extends JsonResource {
    use Searchable;
    public function toArray($request) {
        $docente = Docente::find($this->docente);
        $atividade = Atividade::find($this->atividade);
        $sala = Sala::find($this->sala);

        $t = strtotime($this->data);
        $data = date("Y-m-d", $t);
        $hora = date("H:i:s", $t);
        
        return [
            'id' => $this->id,
            'atividade' => $atividade->nome,
            'docente' => $docente->nome,
            'sala' => $sala->nome,
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