<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class WorkshopsAtividade extends Model {
    public function atividade() {
        return $this->belongsTo('App\Atividade', 'id', 'atividade');
    }

    public function workshop() {
        return $this->belongsTo('App\Workshop', 'id', 'workshop');
    }

    protected $fillable = [
        'atividade', 'workshop', 'data', 'descricao',
    ];
}
