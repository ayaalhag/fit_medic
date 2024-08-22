<?php

//use App\Http\Controllers\Categories\AnimalCategorieController;

use App\Http\Controllers\Animal\AnimalCategorieController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Breeder\Auth_BreederController;
use App\Http\Controllers\Veterinarian\Auth_VeterinarianController;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::controller(AuthController::class)->group(function () {
    Route::post('login', 'login');
    Route::post('register', 'register');
    Route::post('logout', 'logout');
    Route::post('refresh', 'refresh');

});


//
Route::group(['prefix' => 'veterinarian'], function () {

    Route::controller(Auth_VeterinarianController::class)->group(function () {
        Route::post('auth/register-veterinarian', 'register_veterinarian')->name('auth.register_veterinarian');
        Route::post('auth/login-veterinarian', 'login_veterinarian')->name('auth.login_veterinarian');

        // Refresh auth Token

        Route::group(['middleware' => ['auth:veterinarian']], function () {


            //logout
            Route::Post('auth/logout-veterinarian', 'logout_veterinarian')->name('auth.logout');
        });
    });
});


Route::group(['prefix' => 'breeder'], function () {

    Route::controller(Auth_BreederController::class)->group(function () {
        Route::post('auth/register-breeder', 'register_breeder')->name('auth.register_breeder');
        Route::post('auth/login-breeder', 'login_breeder')->name('auth.login_breeder');

        // Refresh auth Token

        Route::group(['middleware' => ['auth:breeder']], function () {


            //logout
            Route::Post('auth/logout-breeder', 'logout_breeder')->name('auth.logout');
        });
    });
});

Route::group(['prefix' => 'categories'], function () {

    Route::controller(AnimalCategorieController::class)->group(function () {
        Route::post('add/animal_categorey', 'add_categorey')->name('add_categorey');
        Route::post('Edit/animal_categorey/{id}', 'update_categorey')->name('update_categorey');
        Route::get('get/animal_categorey', 'get_categories')->name('get_categories');
        Route::delete('delete/animal_categorey/{id}', 'delete_categories')->name('delete_categories');



    });
});



