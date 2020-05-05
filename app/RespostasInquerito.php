<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RespostasInquerito extends Model
{
    public $timestamps = FALSE;

    public function inquerito() {
        return $this->belongsTo('App\Inquerito', 'id');
    }

    protected $fillable = [
        'data', 'inquerito_id', 
        'resposta1', 'resposta2', 'resposta3', 'resposta4', 'resposta5', 'resposta6', 'resposta7', 'resposta8', 'resposta9', 'resposta10' 
    ];
}
