<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Curso extends Model {
    public $timestamps = FALSE;
    
    public function tipo_curso() {
        return $this->belongsTo('App\TipoCurso', 'id', 'tipo');
    }

    protected $fillable = [
        'abreviatura', 'nome', 'tipo', 'semestres',
        'ECTS', 'vagas', 'contato', 'objetivos', 'fotografia',
    ];
}