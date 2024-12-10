<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SystemuserController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\RouteController;
use App\Http\Controllers\CenterController;
use App\Http\Controllers\LabController;
use App\Http\Controllers\LabAssignController;
use App\Http\Controllers\LabAssign1Controller;

Route::get('/', function () {
    return view('login');
});

// Login routes
Route::post('/', [AuthController::class, 'login'])->name('login.form');

// Logout route
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

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

Route::get('/Incharge/labassign', function () {
    return view('incharge.labassign');
});

Route::get('/Incharge/rassign', function () {
    return view('incharge.rassign');
});

Route::get('/Incharge/transaction', function () {
    return view('incharge.transaction');
});

//lab assign
Route::post('/Incharge/labassigns', [LabAssign1Controller::class, 'indexLabassign'])->name('Incharge.labassign.index');
Route::post('/Incharge/labassigns/store', [LabAssign1Controller::class, 'storeLabassigns'])->name('Incharge.labassigns.store');


// ----------------------Supervisor-----------------------
// Route::get('/', function () {
//     return view('incharge.login');
// });

Route::get('/nav', function () {
    return view('supervisor.navbar');
});

Route::get('/Supervisor/dashboard', function () {
    return view('supervisor.dashboard');
});

Route::get('/Supervisor/labassign', function () {
    return view('supervisor.labassign');
});

Route::get('/Supervisor/rassign', function () {
    return view('supervisor.rassign');
});

Route::get('/Supervisor/transaction', function () {
    return view('supervisor.transaction');
});

//lab assign
Route::post('/Supervisor/labassigns', [LabAssign1Controller::class, 'indexLabassign'])->name('Supervisor.labassign.index');
Route::post('/Supervisor/labassigns/store', [LabAssign1Controller::class, 'storeLabassigns'])->name('Supervisor.labassigns.store');

//lab assign
// Route::post('/Incharge/labassigns', [LabAssignController::class, 'indexLabassign'])->name('supervisor.labassign.index');
// Route::post('/Incharge/labassigns/store', [LabAssignController::class, 'storeLabassigns'])->name('supervisor.labassigns.store');


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


//lab assign
Route::get('/admin/labassigns', [LabAssignController::class, 'indexLabassign'])->name('admin.labassign.index');
Route::post('/admin/labassigns/store', [LabAssignController::class, 'storeLabassign'])->name('admin.labassigns.store');
Route::get('/admin/labassigns/{id}/edit', [LabAssignController::class, 'editLabassign'])->name('admin.labassigns.edit');
Route::put('/admin/labassigns/{id}/update', [LabAssignController::class, 'updateLabassign'])->name('admin.labassigns.update');
Route::delete('/admin/labassigns/{id}', [LabAssignController::class, 'destroyLabassign'])->name('admin.labassigns.destroy');



Route::get('/get-user-names', [SystemuserController::class, 'getUserNames'])->name('get.user.names');
// Route::get('/systemuser/search', [SystemUserController::class, 'search'])->name('systemuser.search');
Route::get('/get-lab-names', [LabController::class, 'getLabNames'])->name('get.lab.names');

// -------------------------------------------------------


// ---------------------------Incharge------------------------------



// -------------------------------------------------------------

Route::get('/ll', function () {
    return view('ll');
});




