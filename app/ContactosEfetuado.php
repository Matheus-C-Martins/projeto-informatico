<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ContactosEfetuado extends Model {
    public $timestamps = FALSE;

    public function contactos_escolas() {
        return $this->hasMany('App\ContactosEscola', 'id', 'contacto');
    }

    protected $fillable = [
        'data', 'contacto', 'descricao', 'tipo',
    ];
}
