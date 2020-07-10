<?php

namespace App\Exports;

use App\Participante;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;

class ParticipantesAcademiaExport implements FromQuery {
    use Exportable;

    public function __construct(int $academia) {
        $this->academia = $academia;
    }

    public function query() {
        return Participante::where('academia_de_verao', $this->academia);
    }
}
