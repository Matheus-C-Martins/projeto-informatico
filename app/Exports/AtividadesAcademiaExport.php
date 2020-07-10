<?php

namespace App\Exports;

use App\AtividadeAVerao;

use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;

class AtividadesAcademiaExport implements FromQuery {
    use Exportable;

    public function __construct(int $academia) {
        $this->academia = $academia;
    }

    public function query() {
        return AtividadeAVerao::where('academia_de_verao', $this->academia);
    }
}
