<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Laravel\Scout\Searchable;

class Escola extends JsonResource {
    use Searchable;
    public function toArray($request) {
        return [
            'id' => $this->id,
            'nome' => $this->nome,
            'localizacao' => ($this->localizacao)?$this->localizacao:'-----',
        ];
    }

    public function toSearchableArray() {
       return [
          'id' => $this->id
       ];
    }
}
