<?php

namespace App\Http\Controllers;

use App\Imports\SalasImport;
use App\Http\Requests\CsvImportRequest;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ImportControllerAPI extends Controller {
    public function importSalas(CsvImportRequest $request) {
        try {
            Excel::import(new SalasImport, request()->file('csv_file'));
            return response()->json('Salas importadas com sucesso', 200);
        } catch (\Throwable $th) {
            return response()->json('Não foi inserido um ficheiro .csv', 202);
        }        
    }
}
