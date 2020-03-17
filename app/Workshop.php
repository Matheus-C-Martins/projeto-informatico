<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Workshop extends Model {
    public function workshops_atividade() {
        return $this->belongsTo('App\DocentesAtividade', 'Workshop');
    }

    protected $fillable = [
        'Nome do Workshop',
    ];
}
