<?php

namespace App\Http\Controllers;

use App\Exports\AtividadesAcademiaExport;
use App\Exports\ParticipantesAcademiaExport;
use App\Exports\DocenteAtividadesExport;
use App\Exports\AtividadesExport;
use App\Exports\ContactosExport;
use App\Exports\DocentesExport;
use App\Exports\EscolasExport;
use App\Exports\SalasExport;
use App\Exports\WorkshopsExport;
use Maatwebsite\Excel\Facades\Excel;

use App\DocenteAtividade;
use App\Atividade;
use App\Http\Resources\DocenteAtividades as DocenteAtividadesResource;

class ExportControllerAPI extends Controller {
    public function exportSalas() {
        return Excel::download(new SalasExport, 'salas.csv');
    }

    public function exportWorkshops() {
        return Excel::download(new WorkshopsExport, 'workshops.csv');
    }

    public function exportEscolas() {
        return Excel::download(new EscolasExport, 'escolas.csv');
    }

    public function exportDocentes() {
        return Excel::download(new DocentesExport, 'docentes.csv');
    }
    
    public function exportContactos() {
        return Excel::download(new ContactosExport, 'contactos.csv');
    }

    public function exportAtividades() {
        return Excel::download(new AtividadesExport, 'atividades.csv');
    }

    public function exportDocenteAtividades($id) {
        $atividades = DocenteAtividade::where('docente', $id)->get();
        $aux = [];
        $i = 0;
        foreach ($atividades as $atividade) {
            $a = Atividade::find($atividade->atividade);
        
            $t = strtotime($a->data);
            $data = date("Y-m-d", $t);
            $hora = date("H:i:s", $t);
            $aux[$i] = collect([
                'id' => $atividade->id,
                'data' => $data,
                'hora' => $hora,
                'tipo_de_atividade' => $a->tipo_de_atividade,
                'descricao_participacao' => ($atividade->descricao_participacao)?$atividade->descricao_participacao:'',
            ]);
            $aux[$i]->toArray();
            $i = $i +1;
        }
        $aux = new DocenteAtividadesExport($aux);
        return Excel::download($aux, 'docente_atividades.csv');
    }

    public function exportParticipantesAcademia($id) {
        return Excel::download(new ParticipantesAcademiaExport($id), 'academia_participantes.csv');
    }

    public function exportAtividadesAcademia($id) {
        return Excel::download(new AtividadesAcademiaExport($id), 'academia_atividades.csv');
    }
}
