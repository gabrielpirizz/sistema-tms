<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EntregaController;

// Redireciona para a rota de busca de entregas, tornando a página inicial a tela de busca.
Route::get('/', function () {
    return redirect()->route('entregas.index');
});

Route::prefix('entregas')->name('entregas.')->group(function () {
    Route::get('/', [EntregaController::class, 'index'])->name('index');
    Route::post('/buscar', [EntregaController::class, 'buscar'])->name('buscar');
    Route::get('/listar', [EntregaController::class, 'listar'])->name('listar');
    Route::get('/detalhar/{id}', [EntregaController::class, 'detalhar'])->name('detalhar');
});