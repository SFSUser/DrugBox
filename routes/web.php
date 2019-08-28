<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    //Generar un usuario...
    $user = null;
    if(\Illuminate\Support\Facades\Auth::guest()){
        $user = factory(\App\User::class)->create();
        $medicine = factory(\App\Medicine::class)->create();
    }
    return view('welcome', ["user_data" => $user]);
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

//Soporta todos los métodos: get, post, delete, put, etc...
Route::any("/crud/medicine", 'CRUDController@medicine')->name("crud_medicine");

//Limita solo a obtener el listado :)
Route::get("/crud/user", 'CRUDController@user')->name("crud_user");