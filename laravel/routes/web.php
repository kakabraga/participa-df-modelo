<?php

use App\Http\Controllers\ImportacaoPedidoController;
use App\Http\Controllers\PedidoController;
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
Route::post('/anallisar', [PedidoController::class, 'storeTexto'])->name('pedido.analisar');
// Route::get('/teste-ocr', function () {
//     $ocr = app(\App\Services\OcrService::class);
//     return nl2br($ocr->extrairTexto(storage_path('app/teste.jpg')));
// })->name('pedido.jpg');
// Route::get('/test-config', function () {
//     dd(config('services.ocr.tesseract_path'));
// })->name('teste');
