<?php

namespace App\Http\Controllers;

use Throwable;
use App\Imports\SalasImport;
use App\Imports\EscolasImport;
use App\Imports\DocentesImport;
use App\Imports\ContactosImport;
use App\Imports\WorkshopsImport;
use App\Http\Requests\CsvImportRequest;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Validators\ValidationException;

class ImportControllerAPI extends Controller {
    public function importSalas(CsvImportRequest $request) {
        try {
            Excel::import(new SalasImport, request()->file('csv_file'), null, \Maatwebsite\Excel\Excel::CSV);
            return response()->json('Salas importadas com sucesso', 200);
        } catch (ValidationException $e) {
            $failures = $e->failures();            
            foreach ($failures as $failure) {
                return response()->json($failure->errors()[0].$failure->values()['nome'].", Edifício".$failure->values()['edificio'], 202);
            }
        } catch (Throwable $th){
            return response()->json('Não foi inserido um ficheiro .csv ou o ficheiro não está no formato correto', 202);
        }
    }

    public function importEscolas(CsvImportRequest $request) {
        try {
            Excel::import(new EscolasImport, request()->file('csv_file'), null, \Maatwebsite\Excel\Excel::CSV);
            return response()->json('Escolas importadas com sucesso', 200);
        } catch (ValidationException $e) {
            $failures = $e->failures();            
            foreach ($failures as $failure) {
                return response()->json($failure->errors()[0].$failure->values()['nome'], 202);
            }
        } catch (Throwable $th){ 
            return response()->json('Não foi inserido um ficheiro .csv ou o ficheiro não está no formato correto', 202);
        }
    }

    public function importDocentes(CsvImportRequest $request) {
        try {
            Excel::import(new DocentesImport, request()->file('csv_file'), null, \Maatwebsite\Excel\Excel::CSV);
            return response()->json('Docentes importados com sucesso', 200);
        } catch (ValidationException $e) {
            $failures = $e->failures();            
            foreach ($failures as $failure) {
                return response()->json($failure->errors()[0].$failure->values()['email'].", ou com o telefone interno: ".$failure->values()['telefone_interno'], 202);
            }
        } catch (Throwable $th){ 
            return response()->json('Não foi inserido um ficheiro .csv ou o ficheiro não está no formato correto', 202);
        }
    }

    public function importContactos(CsvImportRequest $request) {
        try {
            Excel::import(new ContactosImport, request()->file('csv_file'), null, \Maatwebsite\Excel\Excel::CSV);
            return response()->json('Contactos importados com sucesso', 200);
        } catch (ValidationException $e) {
            $failures = $e->failures();            
            foreach ($failures as $failure) {
                return response()->json($failure->errors()[0].$failure->values()['email'].", ou com o telefone: ".$failure->values()['telefone'], 202);
            }
        } catch (Throwable $th){ 
            return response()->json('Não foi inserido um ficheiro .csv ou o ficheiro não está no formato correto', 202);
        }
    }

    public function importWorkshops(CsvImportRequest $request) {
        try {
            Excel::import(new WorkshopsImport, request()->file('csv_file'), null, \Maatwebsite\Excel\Excel::CSV);
            return response()->json('Workshops importados com sucesso', 200);
        } catch (ValidationException $e) {
            $failures = $e->failures();            
            foreach ($failures as $failure) {
                return response()->json($failure->errors()[0].$failure->values()['nome'], 202);
            }
        } catch (Throwable $th){
            return response()->json('Não foi inserido um ficheiro .csv ou o ficheiro não está no formato correto', 202);
        }
    }
}
