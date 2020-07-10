<?php

namespace App\Exports;

use App\DocenteAtividadeExport;
use Maatwebsite\Excel\Concerns\FromArray;

class DocenteAtividadesExport implements FromArray {
    protected $atividades;

    public function __construct(array $atividades) {
        $this->atividades = $atividades;
    }

    public function array(): array {
        return $this->atividades;
    }
}
