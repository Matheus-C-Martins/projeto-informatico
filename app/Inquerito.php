<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Inquerito extends Model
{
    public $timestamps = FALSE;

    public function respostas_inquerito() {
        return $this->hasMany('App\RespostasInquerito');
    }

    protected $fillable = [
        'data', 'codigo_acesso', 'estado_codigo',
        'tipo1', 'pergunta1', 'tipo2', 'pergunta2', 'tipo3', 'pergunta3', 'tipo4', 'pergunta4', 'tipo5', 'pergunta5',
        'tipo6', 'pergunta6', 'tipo7', 'pergunta7', 'tipo8', 'pergunta8', 'tipo9', 'pergunta9', 'tipo10', 'pergunta10', 
    ];
}
