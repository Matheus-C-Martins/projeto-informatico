<?php

namespace App\Http\Resources;

use App\Contacto;
use Illuminate\Http\Resources\Json\JsonResource;
use Laravel\Scout\Searchable;

class EscolasContactos extends JsonResource {
    use Searchable;
    public function toArray($request) {
        $contacto = Contacto::find($this->contacto);
        
        return [
            'id' => $this->id,
            'tipo' => ($this->tipo),
            'nome' => ($contacto==null?"-----":$contacto->nome),
            'email' => ($this->tipo!='Email'?"-----":$contacto->email),
            'telefone' => ($this->tipo!='Telefone'?"-----":$contacto->telefone),
            'descricao' => ($this->descricao)?$this->descricao:'',
        ];
    }

    public function toSearchableArray() {
       return [
          'id' => $this->id
       ];
    }
}
