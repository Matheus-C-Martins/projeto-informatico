<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DocenteAtividade extends Model {
    public $timestamps = FALSE;

    public function atividade() {
        return $this->belongsTo('App\Atividade', 'id', 'atividade');
    }

    public function docente() {
        return $this->belongsTo('App\Docente', 'id', 'docente');
    }

    protected $fillable = [
        'atividade', 'docente', 'descricao_participacao',
    ];
}
