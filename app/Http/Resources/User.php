<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Laravel\Scout\Searchable;

class User extends JsonResource {
    use Searchable;
    public function toArray($request) {   
        $aux = 'Nome:'.$this->nome.', email:'.$this->email;
        return [
            'id' => $this->id,
            'aux' => $aux,
        ];
    }

    public function toSearchableArray() {
       return [
          'id' => $this->id,
       ];
    }
}