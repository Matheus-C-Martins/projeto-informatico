<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DocentesAtividade extends Model {
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
