<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Laravel\Scout\Searchable;

class Docente extends JsonResource {
    use Searchable;
    public function toArray($request) {
        return [
            'id' => $this->id,
            'nome' => $this->nome,
            'email' => ($this->email)?$this->email:'-----',
            'telemovel' => ($this->telemovel)?$this->telemovel:'-----',
            'telefone_interno' => ($this->telefone_interno)?$this->telefone_interno:'-----',
        ];
    }

    public function toSearchableArray() {
       return [
          'id' => $this->id
       ];
    }
}
