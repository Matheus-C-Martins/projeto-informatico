<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Contacto extends Model {
    public function atividade() {
        return $this->hasMany('App\Atividade', 'Contacto', 'id');
    }

    public function contactos_escolas() {
        return $this->belongsTo('App\ContactosEscolas', 'Escola');
    }

    protected $fillable = [
        'Nome', 'Telefone', 'E-mail', 'Sexo',
    ];
}
