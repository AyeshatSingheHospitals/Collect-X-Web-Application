<?php

namespace App\Http\Controllers;
use App\Models\Transaction;
use App\Models\TransactionLog;
use Illuminate\Support\Facades\DB;

use Illuminate\Http\Request;

class ShortageController extends Controller
{
    public function index()
    {
        // Get the sum of difference_amount grouped by center
        $centerBalances = Transaction::select(
                'transaction.cid',
                DB::raw('SUM(transaction.difference_amount) as total_difference'),
                'center.centername as centername'  // Using centername instead of name
            )
            ->join('center', 'transaction.cid', '=', 'center.cid')
            ->groupBy('transaction.cid', 'center.centername')
            ->get();

        return view('Admin.shortage', compact('centerBalances'));
    }

    public function indexSupervisor()
    {
        // Get the sum of difference_amount grouped by center
        $centerBalances = Transaction::select(
                'transaction.cid',
                DB::raw('SUM(transaction.difference_amount) as total_difference'),
                'center.centername as centername'  // Using centername instead of name
            )
            ->join('center', 'transaction.cid', '=', 'center.cid')
            ->groupBy('transaction.cid', 'center.centername')
            ->get();

        return view('supervisor.shortage', compact('centerBalances'));
    }

    public function indexIncharge()
    {
        // Get the sum of difference_amount grouped by center
        $centerBalances = Transaction::select(
                'transaction.cid',
                DB::raw('SUM(transaction.difference_amount) as total_difference'),
                'center.centername as centername'  // Using centername instead of name
            )
            ->join('center', 'transaction.cid', '=', 'center.cid')
            ->groupBy('transaction.cid', 'center.centername')
            ->get();

        return view('incharge.shortage', compact('centerBalances'));
    }
}
