<?php

namespace App\Exports;

use App\Sala;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithStrictNullComparison;

class SalasExport implements FromCollection, WithStrictNullComparison {
    public function collection() {
        return Sala::all();
    }
}
