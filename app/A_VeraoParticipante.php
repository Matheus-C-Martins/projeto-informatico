<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class A_VeraoParticipante extends Model {
    public function atividades_a_verao() {
        return $this->belongsTo('App\AtividadeVerao', 'id', 'Atividade');
    }

    public function participante() {
        return $this->belongsTo('App\Participante', 'id', 'Participante');
    }

    protected $fillable = [
        'Atividade', 'Participante', 'Data'
    ];
}
