<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Laravel\Scout\Searchable;

class Contacto extends JsonResource {
    use Searchable;
    public function toArray($request) {
        $t_complete = '-----';
        switch($this->sexo) {
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
            'nome' => $this->nome,
            'email' => ($this->email)?$this->email:'-----',
            'telefone' => ($this->telefone)?$this->telefone:'-----',
            'sexo' => $t_complete,
        ];
    }

    public function toSearchableArray() {
       return [
          'id' => $this->id
       ];
    }
}
