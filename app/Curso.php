<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Curso extends Model {
    public $timestamps = FALSE;

    protected $fillable = [
        'abreviatura', 'nome', 'tipo', 'semestres',
        'ETCS', 'vagas', 'contato', 'objetivos',
    ];
}