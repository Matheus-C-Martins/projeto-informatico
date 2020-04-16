<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Docente extends Model {
    public function docentes_atividade() {
        return $this->hasMany('App\DocentesAtividade', 'Docente');
    }

    public function a_verao_docente() {
        return $this->hasMany('App\A_VeraoDocente', 'Docente');
    }

    protected $fillable = [
        'Nome', 'Telefone Interno', 'Telemovel', 'Email',
    ];
}
