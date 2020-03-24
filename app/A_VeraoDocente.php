<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class A_VeraoDocente extends Model {
    public function atividades_a_verao() {
        return $this->belongsTo('App\AtividadeVerao', 'id', 'Atividade');
    }

    public function docente() {
        return $this->belongsTo('App\Docente', 'id', 'Docente');
    }

    public function sala() {
        return $this->belongsTo('App\Sala', 'id', 'Sala');
    }

    protected $fillable = [
        'Atividade', 'Docente', 'Sala', 'Data'
    ];
}
