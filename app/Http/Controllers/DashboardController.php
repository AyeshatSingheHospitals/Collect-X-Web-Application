<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Lab;
use App\Models\Route;
use App\Models\Center;
use App\Models\SystemUser;
use App\Models\LabAssign;
use App\Models\Transaction;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // Get the count of labs from the `lab` table
        $labCount = Lab::count();
        $routeCount = Route::count();
        $centerCount = Center::count();
        $labassignCount = LabAssign::count();
        $supervisorCount = SystemUser::where('role', 'Supervisor')->count();
        $inchargeCount = SystemUser::where('role', 'Incharge')->count();
        $roCount = SystemUser::where('role', 'RO')->count();

        // Get the total sales amount from the `transaction` table
        $totalSales = Transaction::sum('amount');

        // Get today's total sales
        $todaySales = Transaction::whereDate('created_at', Carbon::today())->sum('amount');

        // Pass the total sales and today's sales to the view
        return view('Admin.dashboard', compact(
            'labCount', 'routeCount', 'centerCount', 
            'supervisorCount', 'inchargeCount', 'roCount', 
            'labassignCount', 'totalSales', 'todaySales'
        ));
    }
}
