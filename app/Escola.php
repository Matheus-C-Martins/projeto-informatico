<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Escola extends Model {
    public function atividade() {
        return $this->hasMany('App\Atividade', 'escola', 'id');
    }

    public function contactos_escolas() {
        return $this->belongsTo('App\ContactosEscolas', 'escola');
    }

    public function participante() {
        return $this->hasMany('App\Participante', 'escola', 'id');
    }

    protected $fillable = [
        'nome', 'localizacao',
    ];
}
