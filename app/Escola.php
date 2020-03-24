<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Escola extends Model {
    public function atividade() {
        return $this->hasMany('App\Atividade', 'Escola', 'id');
    }

    public function contactos_escolas() {
        return $this->belongsTo('App\ContactosEscolas', 'Escola');
    }

    public function participante() {
        return $this->hasMany('App\Participante', 'Escola', 'id');
    }

    protected $fillable = [
        'Nome', 'Localizacao',
    ];
}
