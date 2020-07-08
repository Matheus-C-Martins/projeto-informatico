<?php

namespace App\Http\Resources;

use App\Atividade;
use Illuminate\Http\Resources\Json\JsonResource;
use Laravel\Scout\Searchable;

class DocenteAtividades extends JsonResource {
    use Searchable;
    public function toArray($request) {
        $atividade = Atividade::find($this->atividade);
        
        $t = strtotime($atividade->data);
        $data = date("Y-m-d", $t);
        $hora = date("H:i:s", $t);

        return [
            'id' => $this->id,
            'atividade' => $atividade->id,
            'data' => $data,
            'hora' => $hora,
            'tipo_de_atividade' => $atividade->tipo_de_atividade,
            'descricao_participacao' => ($this->descricao_participacao)?$this->descricao_participacao:'',
        ];
    }

    public function toSearchableArray() {
       return [
          'id' => $this->id
       ];
    }
}
