<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TarefaController;

// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/', [TarefaController::class, 'index']);
Route::post('/tarefas', [TarefaController::class, 'store']);
Route::put('/tarefas/{tarefa}', [TarefaController::class, 'update']);
Route::delete('/tarefas/{tarefa}', [TarefaController::class, 'destroy']);
Route::post('/tarefas/{tarefa}/up', [TarefaController::class, 'subir']);
Route::post('/tarefas/{tarefa}/down', [TarefaController::class, 'descer']);
Route::post('/tarefas/reordenar', [TarefaController::class, 'reordenar']);

