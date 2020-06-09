<?php

namespace App\Http\Controllers;

use App\Curso;
use Illuminate\Http\Request;
use App\Http\Resources\Curso as CursoResource;

class CursosControllerAPI extends Controller
{
    public function getCursos() {
        return Curso::all();
    }

    public function getFotoCurso(Request $request, $nome) {
        return response()->download(public_path().'/storage/logoCursos/'.$nome, 'Imagem curso');
    }

    public function storage(Request $request) {
        $valid = validator($request->only('abreviatura', 'nome', 'tipo', 'semestres', 'ECTS', 'vagas', 'contato', 'objetivos'), [
            'abreviatura'=> 'required|string',
            'nome' => 'required|string',
            'tipo' => 'required|integer',
            'semestres' => 'required|integer',
            'ECTS' => 'required|integer',
            'vagas' => 'required|integer',
            'contato' => 'required|email',
            'objetivos' => 'required|string'
        ]);

        if ($valid->fails()) {
            $jsonError=response()->json($valid->errors()->all(), 205);
            return response()->json($jsonError);
        }

        $data = request()->only('abreviatura', 'nome', 'tipo', 'semestres', 'ECTS', 'vagas', 'contato', 'objetivos');

        if($request->fotografia) {
            $exploded = explode(',', $request->fotografia);
            $decoded = base64_decode($exploded[1]);
            if(Str::contains($exploded[0], 'jpeg')){
                $extension = 'jpg';
            }else{
                $extension = 'png';
            }
            $count = DB::table('cursos')->count();
            $id = $count + 1;
            //generates the photo file name
            $fileName=$data['abreviatura'].".".$extension;
            //saves the image to public/storage/fotos
            $path = public_path().'/storage/logoCursos/'.$fileName;
            file_put_contents($path, $decoded); //we pass the path and the decoded image
            $data['fotografia'] = $fileName;
        } else {
            $data['fotografia'] = null;
        }
        
        $curso = Curso::create([
            'abreviatura' => $data['abreviatura'],
            'nome' => $data['nome'],
            'tipo' => $data['tipo'],
            'semestres' => $data['semestres'],
            'ECTS' => $data['ECTS'],
            'vagas' => $data['vagas'],
            'contato' => $data['contato'],
            'objetivos' => $data['objetivos'],
            'fotografia' => $data['fotografia'],
        ]);        
        return response()->json("Foi criado um novo curso.", 200);
    }

    public function update(Request $request, $id) {
        $cursoModel = Curso::find($id);

        $valid = validator($request->only('abreviatura', 'nome', 'tipo', 'semestres', 'ECTS', 'vagas', 'contato', 'objetivos'), [
            'abreviatura'=> 'required|string',
            'nome' => 'required|string',
            'tipo' => 'required|integer',
            'semestres' => 'required|integer',
            'ECTS' => 'required|integer',
            'vagas' => 'required|integer',
            'contato' => 'required|email',
            'objetivos' => 'required|string'
        ]);

        if ($valid->fails()) {
            $jsonError=response()->json($valid->errors()->all(), 205);
            return response()->json($jsonError);
        }

        if($request->fotografia) {
            $exploded = explode(',', $request->fotografia);
            $decoded = base64_decode($exploded[1]);

            if(Str::contains($exploded[0], 'jpeg')){
                $extension = 'jpg';
            } else {
                $extension = 'png';
            }

            $fileName=$id . "_".time().".".$extension;

            $path = public_path().'/storage/logoCursos/'.$fileName;
            file_put_contents($path, $decoded);
        } else {
            $fileName = null;
        }

        if($fileName != null) {
            $cursoModel["fotografia"] = $fileName;
        }

        $cursoModel['abreviatura'] = $request['abreviatura'];
        $cursoModel['nome'] = $request['nome'];
        $cursoModel['tipo'] = $request['tipo'];
        $cursoModel['semestres'] = $request['semestres'];
        $cursoModel['ECTS'] = $request['ECTS'];
        $cursoModel['vagas'] = $request['vagas'];
        $cursoModel['contato'] = $request['contato'];
        $cursoModel['objetivos'] = $request['objetivos'];

        $cursoModel->save();

        return response()->json("Curso atualizado com sucesso", 200);
    }

    public function remove($id) {
        $curso = Curso::find($id);

        $user->delete();

        $msg = `Curso removido com sucesso`;
        return response()->json($msg, 200);
    }
}