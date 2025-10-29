<?php

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ImageController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\ChambreController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\PaiementController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\TypeChambreController;

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

Route::post('/services', [ServiceController::class, 'store']);
Route::get('/services', [ServiceController::class, 'index']);
Route::delete('/services/{id}', [ServiceController::class, 'destroy']);
Route::put('/services/{id}', [ServiceController::class, 'update']);

Route::post('/typechambre', [TypeChambreController::class,'store']);
Route::get('/typechambre', [TypeChambreController::class, 'index']);
Route::delete('/typechambre/{id}', [TypeChambreController::class, 'destroy']);
Route::put('/typechambre/{id}', [TypeChambreController::class, 'update']);

Route::post('/chambre', [ChambreController::class,'store']);
Route::get('/chambre', [ChambreController::class, 'index']);
Route::delete('/chambre/{id}', [ChambreController::class, 'destroy']);
Route::put('/chambre/{id}', [ChambreController::class, 'update']);
Route::get('/chambre/disponibles/aujourdhui', [ChambreController::class, 'chambresDisponiblesAujourdhui']);

Route::post('/client', [ClientController::class,'store']);
Route::get('/client', [ClientController::class, 'index']);
Route::delete('/client/{id}', [ClientController::class, 'destroy']);
Route::put('/client/{id}', [ClientController::class, 'update']);

Route::post('/paiement', [PaiementController::class,'store']);


Route::post('/reservation', [ReservationController::class,'store']);
Route::get('/reservation', [ReservationController::class, 'index']);
Route::delete('/reservation/{id}', [ReservationController::class, 'destroy']);

Route::post('/upload-image', [ImageController::class, 'upload']);














Route::post('/users', function (Request $request) {
    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:users,email',
        'password' => 'required|string|min:6',
    ]);

    $user = User::create([
        'name' => $validated['name'],
        'email' => $validated['email'],
        'password' => Hash::make($validated['password']),
    ]);

    return response()->json($user, 201);
});

Route::prefix('/auth')->controller(UserController::class)->group(function(){
    Route::post('/login','login');
    Route::post('/logout','logout')->middleware('auth:sanctum');
});
