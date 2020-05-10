<?php

namespace App\Http\Resources;

use App\Escola;
use Illuminate\Http\Resources\Json\JsonResource;
use Laravel\Scout\Searchable;

class ContactosEscolas extends JsonResource {
    use Searchable;
    public function toArray($request) {
        $escola = Escola::find($this->escola);
        
        return [
            'id' => $this->id,
            'tipo' => ($this->tipo),
            'escola' => ($escola==null?"-----":$escola->nome),
            'descricao' => ($this->descricao)?$this->descricao:'',
        ];
    }

    public function toSearchableArray() {
       return [
          'id' => $this->id
       ];
    }
}
