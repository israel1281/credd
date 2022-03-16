<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\VerificationController;
use Illuminate\Support\Facades\Route;

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

// Route::get('/test', function () {
//     $number = '0801212993994';
//     $numberSplit = str_split('0801212993994');
//     if ($number[0] === '0') {
//         $newNumber = '234'.join(array_slice($numberSplit, 1, 10));
//     }
//     dd($newNumber);
// });

Route::get('/', function () {
    return redirect(config('app.spa_url'));
});

Route::get('/dashboard', function () {
    return redirect(config('app.spa_url'));
});

Route::get('email/verify/{id}', [VerificationController::class, 'verify'])->name('verification.verify');

Route::post('email/resend', [VerificationController::class, 'resend'])->middleware('auth')->name('verification.resend');
