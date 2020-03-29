<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Workshop extends Model {
    public function workshops_atividade() {
        return $this->belongsTo('App\WorkshopsAtividade', 'Workshop');
    }

    protected $fillable = [
        'Nome do Workshop',
    ];
}
