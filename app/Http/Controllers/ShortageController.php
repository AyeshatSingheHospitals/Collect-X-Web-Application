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
        // Get the sum of difference_amount grouped by center with lab information
        $centerBalances = Transaction::select(
            'transaction.cid',
            'center.lid', // Lab ID
            DB::raw('SUM(transaction.difference_amount) as total_difference'),
            'center.centername',
            'lab.name as labname' // Lab name
        )
        ->join('center', 'transaction.cid', '=', 'center.cid')
        ->join('lab', 'center.lid', '=', 'lab.lid')
        ->groupBy('transaction.cid', 'center.centername', 'center.lid', 'lab.name')
        ->get();

        return view('supervisor.shortage', compact('centerBalances'));
    }

    public function indexIncharge()
    {
        // Get the sum of difference_amount grouped by center with lab information
        $centerBalances = Transaction::select(
                'transaction.cid',
                'center.lid', // Lab ID
                DB::raw('SUM(transaction.difference_amount) as total_difference'),
                'center.centername',
                'lab.name as labname' // Lab name
            )
            ->join('center', 'transaction.cid', '=', 'center.cid')
            ->join('lab', 'center.lid', '=', 'lab.lid')
            ->groupBy('transaction.cid', 'center.centername', 'center.lid', 'lab.name')
            ->get();

        return view('incharge.shortage', compact('centerBalances'));
    }
}
