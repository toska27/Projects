<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
//use Illuminate\Support\Facades\App;
use App\Http\Controllers\GenreController;
use App\Http\Controllers\PersonController;
use App\Http\Controllers\FilmController;

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

Route::get('/lang/{locale}', function (string $locale) {
    //App::setLocale($locale);
    session(['locale' => $locale]);

    //povratak na prethodnu stranicu
    return redirect()->back();
})->whereIn('locale', ['en', 'sr'])->name('lang');

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Prikaz svih podataka
    Route::get('/genre', [GenreController::class, 'index'])->name('genre.index');
    Route::get('/person', [PersonController::class, 'index'])->name('person.index');

    // Prikaz forme za unos
    Route::get('/genre/create', [GenreController::class, 'create'])->name('genre.create');
    Route::get('/person/create', [PersonController::class, 'create'])->name('person.create');

    // Validacija podatak i upis novog reda
    Route::post('/genre', [GenreController::class, 'store'])->name('genre.store');
    Route::post('/person', [PersonController::class, 'store'])->name('person.store');

    //forma za izmenu podatka
    Route::get('/genre/{genre}/edit', [GenreController::class, 'edit'])->name('genre.edit');
    Route::get('/person/{person}/edit', [PersonController::class, 'edit'])->name('person.edit');

    //izmena postojećeg podatka
    Route::put('/genre/{genre}', [GenreController::class, 'update'])->name('genre.update');
    Route::put('/person/{person}', [PersonController::class, 'update'])->name('person.update');

    //brisanje podatka
    Route::delete('/genre/{genre}', [GenreController::class, 'destroy'])->name('genre.destroy');
    Route::delete('/person/{person}', [PersonController::class, 'destroy'])->name('person.destroy');

    //definisanje svih sedam ruta za kontrolera
    Route::resource('film', FilmController::class);

    Route::post('/film', [FilmController::class, 'index'])->name('film.index');



});

require __DIR__.'/auth.php';

Auth::routes([
    'register' => false,
    'reset' => false,
    'verify' => false
]);

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
