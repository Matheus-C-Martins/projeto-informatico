<?php

namespace App\Http\Resources;

use App\Docente;
use Illuminate\Http\Resources\Json\JsonResource;
use Laravel\Scout\Searchable;

class AtividadeDocentes extends JsonResource {
    use Searchable;
    public function toArray($request) {
        $docente = Docente::find($this->docente);
        
        return [
            'id' => $this->id,
            'docente' => $docente->id,
            'docente_nome' => $docente->nome,
            'descricao_participacao' => ($this->descricao_participacao)?$this->descricao_participacao:'',
        ];
    }

    public function toSearchableArray() {
       return [
          'id' => $this->id
       ];
    }
}
