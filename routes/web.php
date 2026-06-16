<?php

declare(strict_types=1);

use App\Http\Controllers\CharacterController;
use Illuminate\Support\Facades\Route;

Route::get('/', [CharacterController::class, 'index'])->name('characters.index');
Route::get('/{id}', [CharacterController::class, 'show'])->name('characters.show');
