<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Participante extends Model {
    public function a_verao_participante() {
        return $this->belongsTo('App\A_VeraoParticipante', 'Participante');
    }

    public function escola() {
        return $this->belongsTo('App\Escola', 'id', 'Escola');
    }

    public function academia_verao() {
        return $this->belongsTo('App\AcademiaVerao', 'Participante');
    }

    protected $fillable = [
        'Nome', 'Escola', 'Ano', 'Curso', 'Data de Nascimento',
        'Contacto do Aluno', 'Contacto do EE', 'Encarregado de Educacao', 'Regiao',
    ];
}
