<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DocentesAtividade extends Model {
    public function atividade() {
        return $this->belongsTo('App\Atividade', 'id', 'Atividade');
    }

    public function docente() {
        return $this->belongsTo('App\Docente', 'id', 'Docente');
    }

    protected $fillable = [
        'Atividade', 'Docente', 'Descricao Participacao',
    ];
}
