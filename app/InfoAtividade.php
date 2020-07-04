<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class InfoAtividade extends Model {
    public $timestamps = FALSE;

    protected $fillable = [
        'nome', 'descricao', 
    ];
}
