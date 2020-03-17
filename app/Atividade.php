<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Atividade extends Model {
    public function escola() {
        return $this->belongsTo('App\Escola', 'id', 'Escola');
    }

    public function contacto() {
        return $this->belongsTo('App\Contacto', 'id', 'Contacto');
    }

    public function workshops_atividade() {
        return $this->hasMany('App\WorkshopsAtividade', 'Atividade');
    }

    public function docentes_atividade() {
        return $this->hasMany('App\DocentesAtividade', 'Atividade');
    }

    protected $fillable = [
        'Escola', 'Turma', 'Ano', 'Numero de Alunos', 'Data',
        'Duracao', 'Contacto', 'Descricao', 'Tipo de Atividade',
    ];
}
