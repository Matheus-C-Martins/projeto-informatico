<?php

namespace App\Http\Resources;

use App\AcademiaDeVerao as Academia;
use Illuminate\Http\Resources\Json\JsonResource;
use Laravel\Scout\Searchable;

class AtividadeAVerao extends JsonResource {
    use Searchable;
    public function toArray($request) {
        $academia = Academia::find($this->academia_de_verao);

        $t = strtotime($academia->data);
        $data = date("Y", $t);

        $t_complete = '-----';
        switch($this->tipo) {
            case 'aula':
                $t_complete = 'Aula';
                break;
            case 'workshop':
                $t_complete = 'Workshop';
                break;
        }
        
        return [
            'id' => $this->id,
            'nome' =>(!$this->nome?"-----":$this->nome),
            'tipo' => $t_complete,
            'observacoes' => ($this->observacoes)?$this->observacoes:'',
            'academia_de_verao' => $data,
        ];
    }

    public function toSearchableArray() {
       return [
          'id' => $this->id
       ];
    }
}