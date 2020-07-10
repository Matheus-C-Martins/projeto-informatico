<?php

namespace App\Exports;

use App\Atividade;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithStrictNullComparison;

class AtividadesExport implements FromCollection, WithStrictNullComparison {
    public function collection() {
        return Atividade::all();
    }
}
