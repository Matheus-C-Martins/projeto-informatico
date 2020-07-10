<?php

namespace App\Exports;

use App\Workshop;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithStrictNullComparison;

class WorkshopsExport implements FromCollection, WithStrictNullComparison {
    public function collection() {
        return Workshop::all();
    }
}
