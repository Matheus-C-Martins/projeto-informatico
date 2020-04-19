<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Atividade extends Model {
    public $timestamps = FALSE;

    public function escola() {
        return $this->belongsTo('App\Escola', 'id', 'escola');
    }

    public function contacto() {
        return $this->belongsTo('App\Contacto', 'id', 'contacto');
    }

    public function workshops_atividade() {
        return $this->hasMany('App\WorkshopsAtividade', 'atividade');
    }

    public function docentes_atividade() {
        return $this->hasMany('App\DocentesAtividade', 'atividade');
    }

    protected $fillable = [
        'escola', 'turma', 'ano', 'numero_de_alunos', 'data',
        'duracao', 'contacto', 'descricao', 'tipo_de_atividade',
    ];
}
