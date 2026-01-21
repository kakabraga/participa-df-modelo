<?php

use App\Http\Controllers\ImportacaoPedidoController;
use App\Http\Controllers\PedidoController;
use App\Http\Controllers\PythonController;
use Illuminate\Support\Facades\Route;
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

Route::get('/', [PedidoController::class, 'index'])->name('home');
Route::post('/analisar', [PedidoController::class, 'storeTexto'])->name('pedido.analisar');
Route::post('/python', [PythonController::class, 'store'])->name('app.python');
// Route::get('/teste-ocr', function () {
//     $ocr = app(\App\Services\OcrService::class);
//     return nl2br($ocr->extrairTexto(storage_path('app/teste.jpg')));
// })->name('pedido.jpg');
// Route::get('/test-config', function () {
//     dd(config('services.ocr.tesseract_path'));
// })->name('teste');
