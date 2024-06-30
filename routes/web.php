<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});


Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::resource('plant', \App\Http\Controllers\PlantController::class);
Route::resource('plantation', \App\Http\Controllers\PlantationController::class);
Route::resource('plantInventory', \App\Http\Controllers\PlantInventoryController::class);
Route::resource('flat', \App\Http\Controllers\FlatController::class);
Route::resource('flatInventory', \App\Http\Controllers\FlatProgressController::class);
Route::resource('expenses', \App\Http\Controllers\ExpensesController::class);
Route::resource('repository',  \App\Http\Controllers\RepositoryController::class);
Route::resource('seeding',  \App\Http\Controllers\SeedingController::class);

Route::post('/fetch-plant-info', [\App\Http\Controllers\PlantController::class, 'fetchPlantInfo'])->name('plant.fetch-info');
Route::post('/fetch-flat-info', [\App\Http\Controllers\FlatController::class, 'fetchFlatInfo'])->name('flat.fetch-info');
Route::get('/profile/edit', [\App\Http\Controllers\ProfileController::class, 'edit'])->name('profile.edit')->middleware('auth');
Route::put('/profile/update', [\App\Http\Controllers\ProfileController::class, 'update'])->name('profile.update')->middleware('auth');
Route::get('/plant-inventory/create-seeding', [\App\Http\Controllers\PlantInventoryController::class, 'create_seeding'])->name('plantInventory.create_seeding');
Route::post('/plantation/fetch-info', [\App\Http\Controllers\PlantationController::class, 'fetchPlantationInfo'])->name('plantation.fetch-info');




Route::get('/faq', function (){return view('faq');})->name('FAQ');
Route::get('/faq2', function (){return view('faq2');})->name('FAQ_2');
Route::get('/plants-by-type/{type}', [\App\Http\Controllers\PlantationController::class, 'getPlantsByType']);


Route::get('/profile', function (){return view('profile.index');})->name('profile');
