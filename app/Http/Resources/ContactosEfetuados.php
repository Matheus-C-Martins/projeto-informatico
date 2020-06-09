<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Laravel\Scout\Searchable;

class ContactosEfetuados extends JsonResource {
    use Searchable;
    public function toArray($request) {
        $t = strtotime($this->data);
        $data = date("Y-m-d",$t);
        
        return [    
            'id' => $this->id,
            'data'=> $data,
            'tipo' => ($this->tipo),
            'descricao' => ($this->descricao)?$this->descricao:'',
        ];
    }

    public function toSearchableArray() {
       return [
          'id' => $this->id
       ];
    }
}
