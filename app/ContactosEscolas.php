<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ContactosEscolas extends Model {
    public function contacto() {
        return $this->hasMany('App\Contacto', 'id', 'Contacto');
    }

    public function escola() {
        return $this->hasMany('App\Escola', 'id', 'Escola');
    }

    public function contactos_efetuados() {
        return $this->hasMany('App\ContactosEfetuados', 'Contacto');
    }

    protected $fillable = [
        'Contacto', 'Tipo', 'Escola', 'Descricao',
    ];
}
