<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Lab;
use App\Models\Route;
use App\Models\Center;
use App\Models\SystemUser;
use App\Models\LabAssign;
use App\Models\RouteAssign;
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
        $routeassignCount = RouteAssign::count();
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
            'labassignCount','routeassignCount', 'totalSales', 'todaySales'
        ));
    }

    public function getLabDetails(Request $request)
    {
        $labId = $request->input('lid');
    
        if (!$labId) {
            return response()->json(['error' => 'Lab ID is required'], 400);
        }
    
        // Total Sales: Only from centers (cid) under the selected lab (lid) for today
        $totalSales = Transaction::whereHas('center', function ($query) use ($labId) {
                $query->where('lid', $labId);
            })
            ->whereDate('created_at', Carbon::today())
            ->sum('amount');
    
        $totalRoutes = Route::where('lid', $labId)->count();
        $totalCenters = Center::where('lid', $labId)->count();
        $totalROs = LabAssign::where('lid', $labId)
            ->whereHas('systemuser', function ($query) {
                $query->where('role', 'RO');
            })
            ->count();
    
        return response()->json([
            'totalSales' => $totalSales,
            'totalRoutes' => $totalRoutes,
            'totalCenters' => $totalCenters,
            'totalROs' => $totalROs,
        ]);
    }
}
