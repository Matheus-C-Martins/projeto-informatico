<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class WorkshopsAtividade extends Model {
    public function atividade() {
        return $this->belongsTo('App\Atividade', 'id', 'Atividade');
    }

    public function docente() {
        return $this->belongsTo('App\Workshop', 'id', 'Workshop');
    }

    protected $fillable = [
        'Atividade', 'Workshop', 'Descricao',
    ];
}
