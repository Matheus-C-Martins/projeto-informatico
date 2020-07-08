<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Participante extends Model {
    public $timestamps = FALSE;

    public function a_verao_participante() {
        return $this->hasMany('App\AVeraoParticipante', 'participante');
    }

    public function escola() {
        return $this->belongsTo('App\Escola', 'id', 'escola');
    }

    public function academia_verao() {
        return $this->belongsTo('App\AcademiaVerao', 'id', 'academia_de_verao');
    }

    protected $fillable = [
        'nome', 'escola', 'ano', 'curso', 'data_de_nascimento', 'contacto_do_aluno',
        'contacto_do_ee', 'encarregado_de_educacao', 'regiao', 'academia_de_verao',
    ];
}
