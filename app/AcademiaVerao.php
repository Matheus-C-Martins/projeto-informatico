<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AcademiaVerao extends Model {
    public function participante() {
        return $this->hasMany('App\Participante', 'academia_de_verao', 'id');
    }

    public function atividade_verao() {
        return $this->hasMany('App\AtividadeVerao', 'academia_de_verao', 'id');
    }

    protected $fillable = [
        'data', 'participante',
    ];
}
