<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\OrphanController;
use App\Http\Controllers\SponsorController;
use App\Http\Controllers\VolunteerController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\HomeDocumentController;
use App\Models\HomeDocumentRecord;


// Home main page
Route::get('/home', function () {
    return view('home.index');
})->name('home');

// 8 Detail Pages (Fetching parsed records)
Route::get('/home/boys-hostel', fn() => view('home.boys', ['records' => HomeDocumentRecord::where('category', 'boys-hostel')->get()]))->name('home.boys');
Route::get('/home/girls-hostel', fn() => view('home.girls', ['records' => HomeDocumentRecord::where('category', 'girls-hostel')->get()]))->name('home.girls');
Route::get('/home/oam', fn() => view('home.oam', ['records' => HomeDocumentRecord::where('category', 'oam')->get()]))->name('home.oam');
Route::get('/home/oaw', fn() => view('home.oaw', ['records' => HomeDocumentRecord::where('category', 'oaw')->get()]))->name('home.oaw');
Route::get('/home/dam', fn() => view('home.dam', ['records' => HomeDocumentRecord::where('category', 'dam')->get()]))->name('home.dam');
Route::get('/home/daw', fn() => view('home.daw', ['records' => HomeDocumentRecord::where('category', 'daw')->get()]))->name('home.daw');
Route::get('/home/mr-mi-m', fn() => view('home.mrim', ['records' => HomeDocumentRecord::where('category', 'mr-mi-m')->get()]))->name('home.mrim');
Route::get('/home/mr-mi-w', fn() => view('home.mriw', ['records' => HomeDocumentRecord::where('category', 'mr-mi-w')->get()]))->name('home.mriw');
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Redirect root to dashboard
Route::get('/', function () {
    return redirect()->route('dashboard');
});

// Excel Upload and Clear Routes (Authenticated)
Route::post('/home/upload/{category}', [HomeDocumentController::class, 'store'])
    ->name('home.document.store')
    ->middleware('auth');

Route::delete('/home/clear/{category}', [HomeDocumentController::class, 'clearCategory'])
    ->name('home.document.clear')
    ->middleware('auth');

/*
|--------------------------------------------------------------------------
| Authenticated Routes
|--------------------------------------------------------------------------
*/

Route::middleware(['auth'])->group(function () {

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('dashboard');

    // Inmates (Orphans)
    Route::post('/orphans/import', [OrphanController::class, 'import'])
        ->name('orphans.import');
    Route::delete('/orphans', [OrphanController::class, 'clearAll'])
        ->name('orphans.clear');
    Route::resource('orphans', OrphanController::class);

    // Volunteers
    Route::resource('volunteers', VolunteerController::class);

    // Sponsors (Staffs)
    Route::post('/sponsors/import', [SponsorController::class, 'import'])
        ->name('sponsors.import');
    Route::delete('/sponsors', [SponsorController::class, 'clearAll'])
        ->name('sponsors.clear');
    Route::resource('sponsors', SponsorController::class);

    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])
        ->name('profile.edit');

    Route::patch('/profile', [ProfileController::class, 'update'])
        ->name('profile.update');

    Route::delete('/profile', [ProfileController::class, 'destroy'])
        ->name('profile.destroy');

    // Global Search
    Route::get('/search', [SearchController::class, 'index'])
        ->name('global.search');
});

/*
|--------------------------------------------------------------------------
| Auth Routes (Login, Register, etc.)
|--------------------------------------------------------------------------
*/
require __DIR__ . '/auth.php';
