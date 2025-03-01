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

    public function getLabDetails(Request $request)
    {
        $labId = $request->input('lid');

        if (!$labId) {
            return response()->json(['error' => 'Lab ID is required'], 400);
        }

        // Fetch data based on the selected lab ID
        $totalSales = Transaction::where('cid', $labId)->sum('amount'); // Sum of amounts for the selected center
        $totalRoutes = Route::where('lid', $labId)->count(); // Count of routes for the selected lab
        $totalCenters = Center::where('lid', $labId)->count(); // Count of centers for the selected lab
        // Get count of ROs assigned to the selected lab
        $totalIncharges = LabAssign::where('lid', $labId)
            ->join('systemuser', 'labassign.uid_assign', '=', 'systemuser.uid')
            ->where('systemuser.role', 'RO') // Filter by "RO" role
            ->count();  // Count of relationship officers for the selected lab

        // Return the data as JSON response
        return response()->json([
            'totalSales' => $totalSales,
            'totalRoutes' => $totalRoutes,
            'totalCenters' => $totalCenters,
            'totalIncharges' => $totalIncharges,
        ]);
    }
}
