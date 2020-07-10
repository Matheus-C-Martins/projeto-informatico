<?php

namespace App\Exports;

use App\Escola;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithStrictNullComparison;

class EscolasExport implements FromCollection, WithStrictNullComparison {
    public function collection() {
        return Escola::all();
    }
}
