<?php

namespace App\Imports;

use App\Docente;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Validators\Failure;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\SkipsErrors;
use Maatwebsite\Excel\Concerns\SkipsFailures;

class DocentesImport implements ToModel, WithHeadingRow, WithValidation, SkipsOnError, SkipsOnFailure {
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    use SkipsErrors, SkipsFailures;

    public function model(array $row) {
        return new Docente([
            'nome' => $row['nome'],
            'telefone_interno' => $row['telefone_interno'],
            'telemovel' => $row['telemovel'],
            'email' => $row['email'],
        ]);
    }

    public function headingRow(): int {
        return 1;
    }

    public function rules(): array {
        return [
            'email' => Rule::unique('docentes', 'email'),
            'telefone_interno' => Rule::unique('docentes', 'telefone_interno'),
        ];
    }

    public function customValidationMessages() {
        return [
            'email.unique' => 'Já existe uma docente com o email: ',
            'telefone_interno.unique' => 'Já existe uma docente com o email: ',
        ];
    }
}
