<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ChatbotController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::post('/preguntar', [ChatbotController::class, 'preguntar']);
Route::get('/', function () {
    return view('chatbot');
});
Route::fallback(function () {
    return view('chatbot');
});
