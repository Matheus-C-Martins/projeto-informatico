<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Docente extends Model {
    public function docentes_atividade() {
        return $this->hasMany('App\DocentesAtividade', 'docente');
    }

    public function a_verao_docente() {
        return $this->hasMany('App\A_VeraoDocente', 'docente');
    }

    protected $fillable = [
        'nome', 'telefone_interno', 'telemovel', 'email',
    ];
}
