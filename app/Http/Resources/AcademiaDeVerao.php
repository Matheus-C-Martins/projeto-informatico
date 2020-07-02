<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Laravel\Scout\Searchable;

class AcademiaDeVerao extends JsonResource {
    use Searchable;
    public function toArray($request) {
        $t = strtotime($this->data);
        $data = date("Y", $t);
        
        return [
            'id' => $this->id,
            'data'=> $data,
        ];
    }

    public function toSearchableArray() {
       return [
          'id' => $this->id
       ];
    }
}