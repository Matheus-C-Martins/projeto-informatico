<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Laravel\Scout\Searchable;

class CodigoInquerito extends JsonResource {
    use Searchable;
    public function toArray($request) {
        $t = strtotime($this->data);
        $data = date("Y-m-d", $t);
        $hora = date("H:i:s", $t);

        $t_complete = '-----';
        switch($this->validade) {
            case '1':
                $t_complete = 'Válido';
                break;
            case '0':
                $t_complete = 'Inválido';
                break;
        }
        
        return [
            'id' => $this->id,
            'nome' => $this->nome,
            'data'=> $data,
            'hora'=> $hora,
            'validade' => $this->validade,
            'validade_string' => $t_complete,
        ];
    }

    public function toSearchableArray() {
       return [
          'id' => $this->id
       ];
    }
}