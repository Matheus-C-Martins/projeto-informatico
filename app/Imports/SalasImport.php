<?php

namespace App\Imports;

use App\Sala;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class SalasImport implements ToModel, WithHeadingRow {
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row) {
        return new Sala([
            'nome' => $row['nome'],
        ]);
    }

    public function headingRow(): int {
        return 1;
    }
}
