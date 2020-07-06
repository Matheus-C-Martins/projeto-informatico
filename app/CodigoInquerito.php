<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CodigoInquerito extends Model {
    
    public function inquerito() {
        return $this->hasMany('App\Inquerito', 'CodigoInquerito', 'id');
    }

    protected $fillable = [
        'nome', 'validade', 'data',
    ];
}