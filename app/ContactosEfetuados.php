<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ContactosEfetuados extends Model {
    public $timestamps = FALSE;

    public function contactos_escolas() {
        return $this->hasMany('App\ContactosEscolas', 'id', 'contacto');
    }

    protected $fillable = [
        'data', 'contacto', 'descricao', 'tipo',
    ];
}
