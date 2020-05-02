<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Curso extends Model {
    protected $fillable = [
        'abreviatura', 'nome', 'tipo', 'semestres', 'ETCS', 'vagas', 'contato', 'objetivos',
    ];
}