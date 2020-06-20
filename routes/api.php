<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Web Routes
|--------------------------------------------------------------------------
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('users/photo/{email}', 'UserControllerAPI@getPhotoByEmail')->middleware('auth:api');
Route::get('users/{email}', 'UserControllerAPI@checkEmailExists');
Route::delete('users/{id}', 'UserControllerAPI@remove')->name('removeUser')->middleware('auth:api')->middleware('isAdmin');
Route::post('users', 'UserControllerAPI@store')->name('storeUser')->middleware('auth:api')->middleware('isAdmin');
Route::put('users/update/{id_user}', 'UserControllerAPI@updateProfile')->name('updateProfile')->middleware('auth:api');

Route::get('contactos', 'ContactoControllerAPI@getContactos')->middleware('auth:api');
Route::get('contactos/{contacto_id}', 'ContactoControllerAPI@getContactosEscolas')->middleware('auth:api');
Route::get('contactos/escolas/{associado_id}', 'ContactoControllerAPI@getContactosEfetuados')->middleware('auth:api');
Route::post('contactos', 'ContactoControllerAPI@store')->name('storeContacto')->middleware('auth:api');
Route::post('contactos/escolas', 'ContactoControllerAPI@associarEscola')->middleware('auth:api');
Route::post('contactos/escolas/{associado_id}', 'ContactoControllerAPI@marcarEfetuado')->middleware('auth:api');
Route::put('contactos/{contacto_id}/update', 'ContactoControllerAPI@update')->name('updateContacto')->middleware('auth:api');
Route::put('contactos/escolas/{associado_id}/update', 'ContactoControllerAPI@updateAssociado')->name('updateContactoAssociado')->middleware('auth:api');
Route::put('contactos/escolas/{associado_id}/{efetuado_id}/update', 'ContactoControllerAPI@updateEfetuado')->name('updateContactoEfetuado')->middleware('auth:api');
Route::delete('contactos/{contacto_id}', 'ContactoControllerAPI@remove')->name('removeContacto')->middleware('auth:api');
Route::delete('contactos/escolas/{associado_id}/{efetuado_id}', 'ContactoControllerAPI@removeEfetuado')->name('removerContactoEfetuado')->middleware('auth:api');
Route::delete('contactos/escolas/{associado_id}', 'ContactoControllerAPI@removeAssociado')->name('removerContactoAssociado')->middleware('auth:api');

Route::get('docentes', 'DocenteControllerAPI@getDocentes')->middleware('auth:api');
Route::post('docentes', 'DocenteControllerAPI@store')->name('storeDocente')->middleware('auth:api');
Route::put('docentes/{docente_id}/update', 'DocenteControllerAPI@update')->name('updateDocente')->middleware('auth:api');
Route::delete('docentes/{docente_id}', 'DocenteControllerAPI@remove')->name('removeDocente')->middleware('auth:api');

Route::get('escolas', 'EscolaControllerAPI@getEscolas')->middleware('auth:api');
Route::get('escolas/{escola_id}', 'EscolaControllerAPI@getEscolasContactos')->middleware('auth:api');
Route::post('escolas', 'EscolaControllerAPI@store')->name('storeEscola')->middleware('auth:api');
Route::put('escolas/{escola_id}/update', 'EscolaControllerAPI@update')->name('updateEscola')->middleware('auth:api');
Route::delete('escolas/{escola_id}', 'EscolaControllerAPI@remove')->name('removeEscola')->middleware('auth:api');

Route::get('atividades', 'AtividadeControllerAPI@getAtividades')->middleware('auth:api');
Route::get('atividades/{atividade_id}', 'AtividadeControllerAPI@getAtividadesWorkshops')->middleware('auth:api');
Route::get('atividadesAno', 'AtividadeControllerAPI@getAtividadesAno')->name('getAtividadesDoAno')->middleware('auth:api');
Route::get('atividades/estatisticas/{tipoEstatistica?}/{tempo?}/{valor?}', 'AtividadeControllerAPI@getEstatisticas');//->name('getEstatisticasAtividades')->middleware('auth:api');
Route::post('atividades', 'AtividadeControllerAPI@store')->name('storeAtividade')->middleware('auth:api');
Route::post('atividades/{atividade_id}', 'AtividadeControllerAPI@associar')->name('associarWorkshop')->middleware('auth:api');
Route::put('atividades/{atividade_id}/update', 'AtividadeControllerAPI@update')->name('updateAtividade')->middleware('auth:api');
Route::put('atividades/{atividade_id}/{workshop_id}/update', 'AtividadeControllerAPI@updateWorkshop')->name('updateWorkshopAssociado')->middleware('auth:api');
Route::delete('atividades/{workshop_id}', 'AtividadeControllerAPI@remove')->name('removeAtividade')->middleware('auth:api');
Route::delete('atividades/{atividade_id}/{workshop_id}', 'AtividadeControllerAPI@desassociarWorkshop')->name('desassociarWorkshop')->middleware('auth:api');

Route::get('workshops', 'AtividadeControllerAPI@getWorkshops')->middleware('auth:api');

Route::post('login', 'LoginControllerAPI@login')->name('login');
Route::post('logout','LoginControllerAPI@logout')->name('logout')->middleware('auth:api');

Route::post('importSalas', 'ImportControllerAPI@importSalas')->middleware('auth:api');
Route::post('importEscolas', 'ImportControllerAPI@importEscolas')->middleware('auth:api');
Route::post('importDocentes', 'ImportControllerAPI@importDocentes')->middleware('auth:api');
Route::post('importContactos', 'ImportControllerAPI@importContactos')->middleware('auth:api');
Route::post('importWorkshops', 'ImportControllerAPI@importWorkshops')->middleware('auth:api');

Route::get('cursosWeb', 'CursosControllerAPI@getCursosWeb')->middleware('auth:api');
Route::get('tipoCursos', 'CursosControllerAPI@getTipoCursos')->middleware('auth:api');
Route::post('cursos', 'CursosControllerAPI@store')->name('storeCurso')->middleware('auth:api');
Route::put('cursos/{curso_id}/update', 'CursosControllerAPI@update')->name('updateCurso')->middleware('auth:api');
Route::delete('cursos/{curso_id}', 'CursosControllerAPI@remove')->name('removeCurso')->middleware('auth:api');

/*
|--------------------------------------------------------------------------
| API Mobile Routes
|--------------------------------------------------------------------------
*/

Route::get('cursos', 'CursosControllerAPI@getCursos');
Route::get('cursos/{nome}', 'CursosControllerAPI@getFotoCurso');

