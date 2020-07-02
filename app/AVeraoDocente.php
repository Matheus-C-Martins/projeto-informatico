<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AVeraoDocente extends Model {
    public $timestamps = FALSE;

    public function atividades_a_verao() {
        return $this->belongsTo('App\AtividadeVerao', 'id', 'atividade');
    }

    public function docente() {
        return $this->belongsTo('App\Docente', 'id', 'docente');
    }

    public function sala() {
        return $this->belongsTo('App\Sala', 'id', 'sala');
    }

    protected $fillable = [
        'atividade', 'docente', 'sala', 'data'
    ];
}
