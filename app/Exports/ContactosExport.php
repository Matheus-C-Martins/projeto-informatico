<?php

namespace App\Exports;

use App\Contacto;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithStrictNullComparison;

class ContactosExport implements FromCollection, WithStrictNullComparison {
    public function collection() {
        return Contacto::all();
    }
}
