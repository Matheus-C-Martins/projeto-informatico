<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ContactosEscolas extends Model {
    public $timestamps = FALSE;

    public function contacto() {
        return $this->hasMany('App\Contacto', 'id', 'contacto');
    }

    public function escola() {
        return $this->hasMany('App\Escola', 'id', 'escola');
    }

    public function contactos_efetuados() {
        return $this->hasMany('App\ContactosEfetuados', 'contacto');
    }

    protected $fillable = [
        'contacto', 'tipo', 'escola', 'descricao',
    ];
}
