<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SystemuserController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\RouteController;
use App\Http\Controllers\CenterController;
use App\Http\Controllers\LabController;


Route::get('/', [AuthController::class, 'showSignInForm'])->name('login');
Route::post('/signin', [AuthController::class, 'signIn'])->name('signin');


Route::get('/', function () {
    return view('login');
});

Route::get('/welcome', function () {
    return view('welcome');
});


// ----------------------Incharge-----------------------
// Route::get('/', function () {
//     return view('incharge.login');
// });

Route::get('/nav', function () {
    return view('incharge.navbar');
});

Route::get('/Incharge/dashboard', function () {
    return view('incharge.dashboard');
});


// -------------------admin-------------------------
Route::get('/sidebar', function () {
    return view('admin.sidebar');
});

Route::get('/admin/dashboard', function () {
    return view('admin.dashboard');
});

Route::get('/centers', function () {
    return view('admin.centers');
});

Route::get('/route', function () {
    return view('admin.route');
});

Route::get('/transaction', function () {
    return view('admin.transaction');
});

Route::get('/rassign', function () {
    return view('admin.rassign');
});

Route::get('/user', function () {
    return view('admin.user');
});

Route::get('/reg', function () {
    return view('admin.register');
});

Route::get('/edit', function () {
    return view('admin.edit');
});

Route::get('/lab', function () {
    return view('admin/lab');
});

Route::get('/labassign', function () {
    return view('admin.labassign');
});

// ------------------------ [ Admin ]------------------------------

//Lab creation
Route::get('/admin/labs', [LabController::class, 'indexLab'])->name('admin.lab.index'); // Show the form
Route::post('/admin/labs/store', [LabController::class, 'storeLabs'])->name('admin.labs.store');   // Handle form submission
Route::put('/admin/labs/{lid}', [LabController::class, 'updateLabs'])->name('admin.lab.update');
Route::delete('/admin/labs/{lid}', [LabController::class, 'destroyLab'])->name('admin.lab.destroy');


//Route Creation
Route::get('/admin/routes', [RouteController::class, 'indexRoute'])->name('admin.route.index');
Route::post('/admin/routes/store', [RouteController::class, 'storeRoutes'])->name('admin.routes.store');
Route::put('/admin/routes/{rid}', [RouteController::class, 'updateRoute'])->name('admin.route.update');
Route::delete('/admin/routes/{rid}', [RouteController::class, 'destroyRoute'])->name('admin.route.destroy');



//systemuser creation
Route::get('/admin/users', [SystemuserController::class, 'indexUser'])->name('admin.user.index');
Route::post('admin/users/store', [SystemuserController::class, 'storeUsers'])->name('admin.users.store');
Route::get('admin/users/{id}/edit', [SystemuserController::class, 'editUser'])->name('admin.users.edit');
Route::post('admin/users/{id}/update', [SystemuserController::class, 'updateUsers'])->name('admin.users.update');


//Center Creation
Route::get('/admin/centers', [CenterController::class, 'indexCenter'])->name('admin.center.index');
Route::post('/admin/centers/store', [CenterController::class, 'storeCenters'])->name('admin.centers.store');

// -------------------------------------------------------



Route::get('/ll', function () {
    return view('ll');
});



Route::get('/get-user-names', [SystemuserController::class, 'getUserNames'])->name('get.user.names');
// Route::get('/systemuser/search', [SystemUserController::class, 'search'])->name('systemuser.search');

Route::get('/get-lab-names', [LabController::class, 'getLabNames'])->name('get.lab.names');
