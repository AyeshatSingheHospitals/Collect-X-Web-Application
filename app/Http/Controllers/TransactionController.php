<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;
use App\Models\Center;
use App\Models\Systemuser;
use App\Models\Transaction;
use App\Models\SMS;



use Illuminate\Http\Request;

class TransactionController extends Controller
{
    

    public function index()
    {
   
        $transactions = Transaction::with(['systemuser', 'center', 'sms'])
            ->get()
            ->map(function ($transaction) {
                return [
                    'tid' => $transaction->tid,
                    'date' => $transaction->created_at,
                    'full_name' => $transaction->systemuser->fname . ' ' . $transaction->systemuser->lname,
                    'center_name' => $transaction->center->centername,
                    'amount' => $transaction->amount,
                    'remark' => $transaction->remark,
                    'sms_description' => $transaction->sms ? $transaction->sms->description : null,
                ];
            });

        return view('Admin.transaction', compact('transactions'));
    }

    public function show($tid)
    {
       
        $transaction = Transaction::with(['systemuser', 'center', 'sms'])->findOrFail($tid);

        $data = [
            'tid' => $transaction->tid,
            'date' => $transaction->created_at,
            'full_name' => $transaction->systemuser->fname . ' ' . $transaction->systemuser->lname,
            'center_name' => $transaction->center->centername,
            'amount' => $transaction->amount,
            'remark' => $transaction->remark,
            'sms_description' => $transaction->sms ? $transaction->sms->description : null,
        ];

        return response()->json($data);
    }

    public function updateAmount(Request $request, $tid)
    {
        $request->validate([
            'amount' => 'required|numeric|min:0',
        ]);

        $transaction = Transaction::findOrFail($tid);
        $transaction->amount = $request->amount;
        $transaction->save();

        return response()->json(['success' => true, 'message' => 'Amount updated successfully!']);
    }

// public function updateAmount(Request $request, $tid)
// {
//     $request->validate([
//         'amount' => 'required|numeric|min:0',
//         'uid' => 'required|exists:systemuser,uid', // Ensure the UID is valid
//     ]);

//     $transaction = Transaction::findOrFail($tid);

//     // Update the amount and the user ID
//     $transaction->amount = $request->amount;
//     $transaction->uid = $request->uid;
//     $transaction->save();

//     return response()->json(['success' => true, 'message' => 'Transaction updated successfully!']);
// }

    public function fetchTransactionsByLab(Request $request)
    {
        $lid = $request->lid;

        // Get all transactions for the given lab (lid)
        $transactions = Transaction::with(['systemuser', 'center', 'sms'])
            ->whereHas('center', function ($query) use ($lid) {
                $query->where('lid', $lid);
            })
            ->get()
            ->map(function ($transaction) {
                return [
                    'tid' => $transaction->tid,
                    'date' => $transaction->created_at->format('d-m-Y H:i A'),
                    'full_name' => $transaction->systemuser->fname . ' ' . $transaction->systemuser->lname,
                    'center_name' => $transaction->center->centername,
                    'amount' => $transaction->amount,
                    'remark' => $transaction->remark,
                    'sms_description' => $transaction->sms ? $transaction->sms->description : null,
                ];
            });

        return response()->json($transactions);
    }

}
