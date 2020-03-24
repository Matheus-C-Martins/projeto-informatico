<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AtividadeVerao extends Model {
    public function a_verao_participante() {
        return $this->hasMany('App\A_VeraoParticipante', 'Atividade');
    }

    public function a_verao_docente() {
        return $this->hasMany('App\A_VeraoDocente', 'Atividade');
    }

    protected $fillable = [
        'Nome', 'Tipo', 'Observacoes'
    ];
}
