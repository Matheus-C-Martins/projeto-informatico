<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class A_VeraoParticipante extends Model {
    public $timestamps = FALSE;

    public function atividades_a_verao() {
        return $this->belongsTo('App\AtividadeVerao', 'id', 'atividade');
    }

    public function participante() {
        return $this->belongsTo('App\Participante', 'id', 'participante');
    }

    protected $fillable = [
        'atividade', 'participante', 'data'
    ];
}
