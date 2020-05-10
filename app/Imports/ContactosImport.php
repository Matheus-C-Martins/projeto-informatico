<?php

namespace App\Imports;

use App\Contacto;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Validators\Failure;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\SkipsErrors;
use Maatwebsite\Excel\Concerns\SkipsFailures;

class ContactosImport implements ToModel, WithHeadingRow, WithValidation, SkipsOnError, SkipsOnFailure {
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    use SkipsErrors, SkipsFailures;

    public function model(array $row) {
        return new Contacto([
            'nome' => $row['nome'],
            'telefone' => $row['telefone'],
            'email' => $row['email'],
            'sexo' => $row['sexo'],
        ]);
    }

    public function headingRow(): int {
        return 1;
    }

    public function rules(): array {
        return [
            'telefone' => Rule::unique('contactos', 'telefone'),
            'email' => Rule::unique('contactos', 'email'),
            'sexo' => Rule::in(['masculino', 'feminino', 'outro']),
        ];
    }

    public function customValidationMessages() {
        return [
            'telefone.unique' => 'Já existe um contacto com o email: ',
            'email.unique' => 'Já existe um contacto com o email: ',
        ];
    }
}
