<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Laravel\Scout\Searchable;

class Inquerito extends JsonResource {
    use Searchable;

    public function toArray($request) {
        $t = strtotime($this->data);
        $data = date("Y-m-d", $t);
        $hora = date("H:i:s", $t);

        return [
            'id' => $this->id,
            'data' => $data,
            'hora' => $hora,
            'resposta1' => $this->resposta1,
            'resposta2' => $this->resposta2,
            'resposta3' => $this->resposta3,
            'resposta4' => $this->resposta4,
            'resposta5' => $this->resposta5,
            'resposta6' => $this->resposta6,
            'sugestao' => ($this->sugestao)?$this->sugestao:'',
        ];
    }

    public function toSearchableArray() {
       return [
          'id' => $this->id
       ];
    }
}
