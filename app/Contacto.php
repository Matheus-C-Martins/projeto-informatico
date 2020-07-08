<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Contacto extends Model {
    public $timestamps = FALSE;

    public function atividade() {
        return $this->hasMany('App\Atividade', 'contacto', 'id');
    }

    public function contactos_escolas() {
        return $this->belongsTo('App\ContactosEscola', 'escola');
    }

    protected $fillable = [
        'nome', 'telefone', 'email', 'sexo',
    ];
}
