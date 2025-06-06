<?php
use App\Http\Controllers\MainController;
use Illuminate\Support\Facades\Route;
use App\Livewire\Post\Crud;
use App\Http\Controllers\Ajax\PostController;
Route::get('/', [PostController::class, 'index']);
Route::get('/ajax-posts', [PostController::class, 'getPosts']);
Route::post('/ajax-posts', [PostController::class, 'store']);
Route::put('/ajax-posts/{id}', [PostController::class, 'update']);
Route::delete('/ajax-posts/{id}', [PostController::class, 'delete']);

Route::get('/livewire-posts', Crud::class)->name('livewire.posts');
