<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Inquerito extends Model
{
    public function codigo_inquerito() {
        return $this->belongsTo('App\CodigoInquerito', 'id', 'codigo_inquerito');
    }

    protected $fillable = [
        'data', 'resp1', 'resp2', 'resp3', 'resp4', 'resp5', 'resp6', 'resp7', 'resp8', 'resp9', 'resp10',
    ];
}
