<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ContactosEfetuados extends Model {
    public function contactos_escolas() {
        return $this->hasMany('App\ContactosEscolas', 'id', 'Contacto');
    }

    protected $fillable = [
        'Data', 'Contacto', 'Descricao', 'Tipo',
    ];
}