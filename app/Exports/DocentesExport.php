<?php

namespace App\Exports;

use App\Docente;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithStrictNullComparison;

class DocentesExport implements FromCollection, WithStrictNullComparison {
    public function collection() {
        return Docente::all();
    }
}
