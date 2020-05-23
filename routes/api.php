<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('users/photo/{email}', 'UserControllerAPI@getPhotoByEmail')->middleware('auth:api'); //->middleware('isUser')
Route::get('users/{email}', 'UserControllerAPI@checkEmailExists');
Route::delete('users/{id}', 'UserControllerAPI@remove')->name('removeUser')->middleware('auth:api'); //->middleware('isAdmin')
Route::post('users', 'UserControllerAPI@store')->name('storeUser')->middleware('auth:api'); //->middleware('isAdmin')
Route::put('users/update/{id_user}', 'UserControllerAPI@updateProfile')->name('updateProfile')->middleware('auth:api'); //->middleware('isAuthUser')

Route::get('contactos', 'ContactoControllerAPI@getContactos')->middleware('auth:api'); //->middleware('isAdmin')
Route::get('contactos/{id}', 'ContactoControllerAPI@getContactosEscolas')->middleware('auth:api'); //->middleware('isAdmin')
Route::get('contactos/escolas/{id}', 'ContactoControllerAPI@getContactosEfetuados')->middleware('auth:api'); //->middleware('isAdmin')
Route::post('contactos', 'ContactoControllerAPI@store')->name('storeContacto')->middleware('auth:api'); //->middleware('isAdmin')
Route::post('contactos/escolas', 'ContactoControllerAPI@associarEscola')->middleware('auth:api'); //->middleware('isAdmin')
Route::post('contactos/escolas/{id}', 'ContactoControllerAPI@marcarEfetuado')->middleware('auth:api'); //->middleware('isAdmin')
Route::put('contactos/{contacto_id}/update', 'ContactoControllerAPI@update')->name('updateContacto')->middleware('auth:api'); //->middleware('isAdmin')
Route::put('contactos/escolas/{associado_id}/update', 'ContactoControllerAPI@updateAssociado')->name('updateContactoAssociado')->middleware('auth:api'); //->middleware('isAdmin')
Route::put('contactos/escolas/{associado_id}/{efetuado_id}/update', 'ContactoControllerAPI@updateEfetuado')->name('updateContactoEfetuado');//->middleware('auth:api'); //->middleware('isAdmin')
Route::delete('contactos/{id}', 'ContactoControllerAPI@remove')->name('removeContacto')->middleware('auth:api'); //->middleware('isAdmin')

Route::get('docentes', 'DocenteControllerAPI@getDocentes')->middleware('auth:api'); //->middleware('isAdmin')
Route::post('docentes', 'DocenteControllerAPI@store')->name('storeDocente')->middleware('auth:api'); //->middleware('isAdmin')
Route::put('docentes/{docente_id}/update', 'DocenteControllerAPI@update')->name('updateDocente')->middleware('auth:api'); //->middleware('isAdmin')
Route::delete('docentes/{id}', 'DocenteControllerAPI@remove')->name('removeDocente')->middleware('auth:api'); //->middleware('isAdmin')

Route::get('escolas', 'EscolaControllerAPI@getEscolas')->middleware('auth:api'); //->middleware('isAdmin')
Route::get('escolas/{id}', 'EscolaControllerAPI@getEscolasContactos');//->middleware('auth:api'); //->middleware('isAdmin')
Route::post('escolas', 'EscolaControllerAPI@store')->name('storeEscola')->middleware('auth:api'); //->middleware('isAdmin')
Route::put('escolas/{escola_id}/update', 'EscolaControllerAPI@update')->name('updateEscola')->middleware('auth:api'); //->middleware('isAdmin')
Route::delete('escolas/{id}', 'EscolaControllerAPI@remove')->name('removeEscola')->middleware('auth:api'); //->middleware('isAdmin')

Route::post('login', 'LoginControllerAPI@login')->name('login');
Route::post('logout','LoginControllerAPI@logout')->name('logout')->middleware('auth:api');

Route::post('importSalas', 'ImportControllerAPI@importSalas')->middleware('auth:api');
Route::post('importEscolas', 'ImportControllerAPI@importEscolas')->middleware('auth:api');
Route::post('importDocentes', 'ImportControllerAPI@importDocentes')->middleware('auth:api');
Route::post('importContactos', 'ImportControllerAPI@importContactos')->middleware('auth:api');
