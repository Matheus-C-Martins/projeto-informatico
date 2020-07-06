<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Inquerito extends Model
{
    public $timestamps = false;

    public function codigo_inquerito() {
        return $this->belongsTo('App\CodigoInquerito', 'id', 'codigo_inquerito');
    }

    protected $fillable = [
        'data', 'resposta1', 'resposta2', 'resposta3', 'resposta4', 'resposta5', 'resposta6', 'sugestao', 'codigo_id'
    ];
}
