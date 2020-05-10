<?php

namespace App\Imports;

use App\Escola;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Validators\Failure;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\SkipsErrors;
use Maatwebsite\Excel\Concerns\SkipsFailures;

class EscolasImport implements ToModel, WithHeadingRow, WithValidation, SkipsOnError, SkipsOnFailure {
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    use SkipsErrors, SkipsFailures;

    public function model(array $row) {
        return new Escola([
            'nome' => $row['nome'],
            'localizacao' => $row['localizacao'],
        ]);
    }

    public function headingRow(): int {
        return 1;
    }

    public function rules(): array {
        return [
            'nome' => Rule::unique('escolas', 'nome'),
        ];
    }

    public function customValidationMessages() {
        return [
            'nome.unique' => 'Já existe uma escola com o nome: ',
        ];
    }
}
