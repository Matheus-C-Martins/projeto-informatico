<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Laravel\Scout\Searchable;

class Contacto extends JsonResource {
    use Searchable;
    public function toArray($request) {
        $t_complete = '-----';
        switch($this->Sexo) {
            case 'masculino':
                $t_complete = 'Masculino';
                break;
            case 'feminino':
                $t_complete = 'Feminino';
                break;
            case 'outro':
                $t_complete = 'Outro';
                break;
        }
        return [
            'id' => $this->id,
            'nome' => $this->Nome,
            'email' => ($this->Email)?$this->Email:'-----',
            'telefone' => ($this->Telefone)?$this->Telefone:'-----',
            'sexo' => $t_complete,
        ];
    }

    public function toSearchableArray() {
       return [
          'id' => $this->id
       ];
    }
}
