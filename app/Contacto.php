<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Contacto extends Model {
    public function atividade() {
        return $this->hasMany('App\Atividade', 'contacto', 'id');
    }

    public function contactos_escolas() {
        return $this->belongsTo('App\ContactosEscolas', 'escola');
    }

    protected $fillable = [
        'nome', 'telefone', 'email', 'sexo',
    ];
}
