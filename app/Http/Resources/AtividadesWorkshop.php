<?php

namespace App\Http\Resources;

use App\Workshop;
use Illuminate\Http\Resources\Json\JsonResource;
use Laravel\Scout\Searchable;

class AtividadesWorkshop extends JsonResource {
    use Searchable;
    public function toArray($request) {
        $workshop = Workshop::find($this->workshop);

        $t = strtotime($this->data);
        $data = date("Y-m-d", $t);
        $hora = date("H:i:s", $t);
        
        return [
            'id' => $this->id,
            'workshop' => ($workshop==null?"-----":$workshop->nome),
            'data'=> $data,
            'hora'=> $hora,
            'descricao' => ($this->descricao)?$this->descricao:'',
        ];
    }

    public function toSearchableArray() {
       return [
          'id' => $this->id
       ];
    }
}
