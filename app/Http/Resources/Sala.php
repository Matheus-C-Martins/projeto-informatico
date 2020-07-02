<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Laravel\Scout\Searchable;

class Sala extends JsonResource {
    use Searchable;
    public function toArray($request) {        
        return [
            'id' => $this->id,
            'nome' => $this->nome,
        ];
    }

    public function toSearchableArray() {
       return [
          'id' => $this->id,
       ];
    }
}