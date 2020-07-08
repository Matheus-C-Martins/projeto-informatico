<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Docente extends Model {
    public $timestamps = FALSE;

    public function docentes_atividade() {
        return $this->hasMany('App\DocenteAtividade', 'docente');
    }

    public function a_verao_docente() {
        return $this->hasMany('App\AVeraoDocente', 'docente');
    }

    protected $fillable = [
        'nome', 'telefone_interno', 'telemovel', 'email',
    ];
}
