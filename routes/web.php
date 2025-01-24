<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SystemuserController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\RouteController;
use App\Http\Controllers\CenterController;
use App\Http\Controllers\LabController;
use App\Http\Controllers\LabAssignController;
use App\Http\Controllers\LabAssign1Controller;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\RouteAssignController;
use App\Http\Controllers\RouteAssignByInchargeController;
use App\Http\Controllers\RouteAssignBySupervisorController;


use App\Http\Controllers\DashboardController;


Route::get('/', function () {
    return view('login');
});

// Login routes
Route::post('/', [AuthController::class, 'login'])->name('login.form');

// Logout route
// Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

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

// Route::get('admin/routeassigns', function () {
//     return view('admin.rassign');
// });

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

Route::get('/admin/dashboard', [DashboardController::class, 'index']);

//Lab creation
Route::get('/admin/labs', [LabController::class, 'indexLab'])->name('admin.lab.index');
Route::post('/admin/labs/store', [LabController::class, 'storeLabs'])->name('admin.labs.store');
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
Route::get('/getRoutes/{labId}', [RouteController::class, 'getRoutes']);
Route::get('/admin/centers', [CenterController::class, 'indexCenter'])->name('admin.center.index');
Route::post('/admin/centers/store', [CenterController::class, 'storeCenters'])->name('admin.centers.store');
Route::get('/getRouteById/{rid}', [CenterController::class, 'getRouteById'])->name('routes.getById');
Route::put('/admin/centers/{cid}', [CenterController::class, 'updateCenters'])->name('admin.center.update');
Route::delete('/admin/centers/{cid}', [CenterController::class, 'destroycenter'])->name('admin.center.destroy');

//lab assign
Route::get('/admin/labassigns', [LabAssignController::class, 'indexLabassign'])->name('admin.labassign.index');
Route::post('/admin/labassigns/store', [LabAssignController::class, 'storeLabassign'])->name('admin.labassigns.store');
// Route::get('/admin/labassigns/{id}/edit', [LabAssignController::class, 'editLabassign'])->name('admin.labassigns.edit');
// Route::put('/admin/labassigns/{id}/update', [LabAssignController::class, 'updateLabassign'])->name('admin.labassigns.update');
Route::put('/admin/labassigns/{laid}', [LabAssignController::class, 'updateLabassign'])->name('admin.labassigns.update');
Route::delete('/admin/labassigns/{id}', [LabAssignController::class, 'destroyLabassign'])->name('admin.labassigns.destroy');

//Route Assign
Route::get('admin/routeassigns', function () {
    return view('admin.rassign');
});
Route::get('admin/route-assign/search', [RouteAssignController::class, 'searchLab']);
Route::get('admin/routeassigns', [RouteAssignController::class, 'showAssignedRoutes']);
Route::post('admin/route-assign/store', [RouteAssignController::class, 'storeAssignments'])->name('route.assign.store');

Route::get('/get-user-names', [SystemuserController::class, 'getUserNames'])->name('get.user.names');
Route::get('/get-lab-names', [LabController::class, 'getLabNames'])->name('get.lab.names');

// transaction

Route::get('/admin/transactions', [TransactionController::class, 'index'])->name('transactions.index');
Route::get('/admin/transactions/{tid}', [TransactionController::class, 'show'])->name('transactions.show');
Route::put('/admin/transaction/{tid}', [TransactionController::class, 'updateAmount'])->name('transaction.updateAmount');

// ----------------------Incharge-----------------------

Route::get('Incharge/dashboard', function () {
    return view('incharge.dashboard');
});

//Lab View
Route::get('incharge/assignedlabs', function () {
    return view('incharge.assignedlabs');
});

Route::get('/incharge/assigned-labs', [LabAssignController::class, 'getAssignedLabs'])->name('incharge.assigned-labs');
Route::get('/lab/assignments', [LabAssignController::class, 'getLabAssignments'])->name('lab.assignments');

//Route Assign
Route::get('incharge/rassign', function () {
    return view('incharge.rassign');
});
Route::get('incharge/rassign', [RouteAssignByInchargeController::class, 'showAssignedRoutes'])->name('incharge.rassign');
Route::get('incharge/route-assign/search', [RouteAssignByInchargeController::class, 'searchLab'])->name('incharge.route.assign.search');
Route::post('incharge/route-assign/store', [RouteAssignByInchargeController::class, 'storeAssignments'])->name('incharge.route-assign.store');

//transaction
Route::get('Incharge/transaction', function () {
    return view('incharge.transaction');
});

// Route::get('/incharge/assigned-labs', [TransactionController::class, 'getAssignedLabs'])->name('incharge.assigned-labs');

// ----------------------supervisor-----------------------
Route::get('supervisor/dashboard', function () {
    return view('supervisor.dashboard');
});

//Lab View
Route::get('Supervisor/assignedlabs', function () {
    return view('supervisor.assignedlabs');
});

Route::get('/supervisor/assigned-labs', [LabAssignController::class, 'getAssignedLabs'])->name('supervisor.assigned-labs');
Route::get('/lab/assignments', [LabAssignController::class, 'getLabAssignments'])->name('lab.assignments');

//Route Assign
Route::get('Supervisor/rassign', function () {
    return view('supervisor.rassign');
});
Route::get('Supervisor/rassign', [RouteAssignBySupervisorController::class, 'showAssignedRoutes'])->name('supervisor.rassign');
Route::get('/route-assign/search', [RouteAssignBySupervisorController::class, 'searchLab'])->name('route.assign.search');
Route::post('/route-assign/store', [RouteAssignBySupervisorController::class, 'storeAssignments'])->name('route-assign.store');

//transaction
Route::get('Supervisor/transaction', function () {
    return view('supervisor.transaction');
});

Route::get('/supervisor/transactions', [TransactionController::class, 'fetchTransactionsByLab'])->name('transactions.byLab');