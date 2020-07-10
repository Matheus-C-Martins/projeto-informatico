<?php

namespace App\Exports;

use App\Curso;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithStrictNullComparison;

class CursosExport implements FromCollection, WithStrictNullComparison {
    public function collection() {
        return Curso::all();
    }
}
