<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AtividadeAVerao extends Model {
    public $timestamps = FALSE;

    public function a_verao_participante() {
        return $this->hasMany('App\AVeraoParticipante', 'atividade');
    }

    public function a_verao_docente() {
        return $this->hasMany('App\AVeraoDocente', 'atividade');
    }

    public function academia_de_verao() {
        return $this->belongsTo('App\AcademiaVerao', 'id', 'academia_de_verao');
    }

    protected $fillable = [
        'nome', 'tipo', 'observacoes', 'academia_de_verao'
    ];
}
