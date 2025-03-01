<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;
use App\Models\Center;
use App\Models\Systemuser;
use App\Models\Transaction;
use App\Models\TransactionLog;
use App\Models\LabAssign;
use App\Models\SMS;
use Illuminate\Http\Request;
use App\Models\HasFactory;

use Playsms\Webservices;

class TransactionController extends Controller
{
    // For admin 
    
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

            $transactions = Transaction::all();
            foreach ($transactions as $transaction) {
                $transaction->sms = SMS::where('tid', $transaction->tid)->get(); // Manually load the related SMS
            }
        return view('Admin.transaction', compact('transactions'));
    }

    public function updateAmount(Request $request, $tid)
    {
        // Find the transaction
        $transaction = Transaction::find($tid);
        if (!$transaction) {
            return redirect()->back()->with('error', 'Transaction not found.');
        }

        // Get the logged-in user
        $user = Systemuser::find(session('uid'));
        if (!$user) {
            return redirect()->back()->with('error', 'User not found.');
        }

        // Get the center related to the transaction
        $center = Center::find($transaction->cid);
        if (!$center) {
            return redirect()->back()->with('error', 'Center not found.');
        }

        // Save the original amount for logging purposes
        $originalAmount = $transaction->amount;

        // Update the transaction amount
        $transaction->amount = $request->amount;
        $transaction->save();

        // Insert a new record into the transactionlogs table
        TransactionLog::create([
            'tid' => $transaction->tid,
            'uid' => $user->uid, // Logged-in user ID
            'rid' => $transaction->rid,
            'cid' => $transaction->cid,
            'amount' => $request->amount,
            'remark' => 'Amount updated from ' . number_format($originalAmount, 2) . ' to ' . number_format($request->amount, 2),
            'action' => 'updated',
        ]);

        // Construct SMS message
        $message = $user->fname . ' ' . $user->lname . ' (' . $user->role . ') updated the amount from LKR ' . 
                   number_format($originalAmount, 2) . ' to LKR ' . number_format($request->amount, 2) .
                   ' for center: ' . $center->centername;

        // Insert a new SMS record into the `sms` table
        Sms::create([
            'tid' => $transaction->tid,
            'description' => $message,
            'phonenumber1' => $center->authorizedcontact,
            'phonenumber2' => $center->selectedcontact,
            'phonenumber3' => $center->thirdpartycontact,
            'phonenumber4' => '', // Include additional phone numbers as needed
            'phonenumber5' => '',
        ]);

        // Send SMS to authorized, selected, and third-party contacts
        $this->sendSms($center->authorizedcontact, $message);
        $this->sendSms($center->selectedcontact, $message);
        $this->sendSms($center->thirdpartycontact, $message);

        return redirect()->route('transactions.index')->with('success', 'Amount updated and SMS sent successfully.');
    }

    private function sendSms($phoneNumber, $message)
    {
        if (!empty($phoneNumber)) {
            $ws = new Webservices();

            // Configure your SMS API credentials
            // $ws->url = 'https://sms.scnev.es/index.php?app=ws'; // Your SMS service URL
            $ws->username = 'rajith'; // Your SMS service username
            $ws->token = '188a51f2390e8a8d26fa090ab3215da8'; // Your SMS service token

            // Set SMS details
            $ws->from = 'Sandeshaya'; // Sender ID / Mask
            $ws->to = $phoneNumber; // Recipient’s phone number
            $ws->msg = $message; // SMS content
            $ws->nofooter = 1; // Disable footer (1) or enable (0)

            // Send SMS
            $ws->sendSms();

            // Log response for debugging
            if ($ws->getStatus()) {
                \Log::info("SMS sent successfully to $phoneNumber. Response: " . json_encode($ws->getData()));
            } else {
                \Log::error("Failed to send SMS to $phoneNumber. Error: " . $ws->getErrorString());
            }
        }
    }

    // --------------------------------Supervisor and Incharge ---------------------------------------------------

    public function getTransactions(Request $request)
    {
        $lid = $request->query('lid');

        // Fetch transactions without eager loading SMS
        $transactions = Transaction::whereHas('center', function ($query) use ($lid) {
                $query->where('lid', $lid);
            })
            ->get();

        // Manually load SMS records
        $formattedTransactions = $transactions->map(function ($transaction) {
            // Fetch SMS records separately
            $smsRecords = SMS::where('tid', $transaction->tid)->get()->map(function ($sms) {
                return ['description' => $sms->description];
            });

            return [
                'tid' => $transaction->tid,
                'date' => $transaction->created_at->format('Y-m-d H:i:s'),
                'full_name' => optional($transaction->systemuser)->fname . ' ' . optional($transaction->systemuser)->lname ?? 'N/A',
                'center_name' => optional($transaction->center)->centername ?? 'N/A',
                'amount' => $transaction->amount, // Format amount
                'remark' => $transaction->remark ?? 'N/A',
                'sms' => $smsRecords->isNotEmpty() ? $smsRecords : [],
            ];
        });

        return response()->json($formattedTransactions);
    }

    // --------------------------supervisor transaction-------------------------

    public function indexSupervisor(Request $request)
    {
        // Get the logged-in user's ID from the session
        $uid = session('uid');

        if (!$uid) {
            return redirect()->route('login')->with('error', 'User not authenticated.');
        }

        // Get assigned labs for the supervisor
        $assignedLabs = LabAssign::where('uid', $uid)->pluck('lid');

        // Fetch transactions for centers that belong to the assigned labs
        $transactions = Transaction::whereHas('center', function ($query) use ($assignedLabs) {
                $query->whereIn('lid', $assignedLabs);
            })
            ->with(['systemuser', 'center'])
            ->get();

        // Format transactions for view
        $formattedTransactions = $transactions->map(function ($transaction) {
            $smsRecords = SMS::where('tid', $transaction->tid)->get()->map(function ($sms) {
                return ['description' => $sms->description];
            });

            return [
                'tid' => $transaction->tid,
                'date' => $transaction->created_at->format('Y-m-d H:i:s'),
                'full_name' => optional($transaction->systemuser)->fname . ' ' . optional($transaction->systemuser)->lname ?? 'N/A',
                'center_name' => optional($transaction->center)->centername ?? 'N/A',
                'amount' => number_format($transaction->amount, 3), // Ensures 3 decimal places
                'remark' => $transaction->remark ?? 'N/A',
                'sms' => $smsRecords->isNotEmpty() ? $smsRecords : [],
            ];
        });

        return view('supervisor.transaction', compact('formattedTransactions', 'assignedLabs'));
    }

    public function updateAmountSupervisor(Request $request, $tid)
    {
        // Validate the request
        $request->validate([
            'amount' => 'required|numeric|min:0',
        ]);

        // Find the transaction
        $transaction = Transaction::find($tid);
        if (!$transaction) {
            return response()->json(['success' => false, 'message' => 'Transaction not found.'], 404);
        }

        // Get the logged-in user
        $user = Systemuser::find(session('uid'));
        if (!$user) {
            return response()->json(['success' => false, 'message' => 'User not found.'], 404);
        }

        // Get the center related to the transaction
        $center = Center::find($transaction->cid);
        if (!$center) {
            return response()->json(['success' => false, 'message' => 'Center not found.'], 404);
        }

        // Save the original amount for logging purposes
        $originalAmount = $transaction->amount;

        // Update the transaction amount
        $transaction->amount = $request->amount;
        $transaction->save();

        // Insert a new record into the transactionlogs table
        TransactionLog::create([
            'tid' => $transaction->tid,
            'uid' => $user->uid, // Logged-in user ID
            'rid' => $transaction->rid,
            'cid' => $transaction->cid,
            'amount' => $request->amount,
            'remark' => 'Amount updated from ' . number_format($originalAmount, 2) . ' to ' . number_format($request->amount, 2),
            'action' => 'updated',
        ]);

        // Construct SMS message
        $message = $user->fname . ' ' . $user->lname . ' (' . $user->role . ') updated the amount from LKR ' . 
                number_format($originalAmount, 2) . ' to LKR ' . number_format($request->amount, 2) .
                ' for center: ' . $center->centername;

        // Insert a new SMS record into the `sms` table
        Sms::create([
            'tid' => $transaction->tid,
            'description' => $message,
            'phonenumber1' => $center->authorizedcontact,
            'phonenumber2' => $center->selectedcontact,
            'phonenumber3' => $center->thirdpartycontact,
            'phonenumber4' => '', // Include additional phone numbers as needed
            'phonenumber5' => '',
        ]);

        // Send SMS to authorized, selected, and third-party contacts
        $this->sendSms($center->authorizedcontact, $message);
        $this->sendSms($center->selectedcontact, $message);
        $this->sendSms($center->thirdpartycontact, $message);

        // Return JSON response for AJAX
        return response()->json(['success' => true, 'message' => 'Amount updated and SMS sent successfully.']);
    }

    // --------------------------Incharge transaction-------------------------

    public function updateAmountIncharge(Request $request, $tid)
    {
        // Find the transaction
        $transaction = Transaction::find($tid);
        if (!$transaction) {
            return redirect()->back()->with('error', 'Transaction not found.');
        }

        // Get the logged-in user
        $user = Systemuser::find(session('uid'));
        if (!$user) {
            return redirect()->back()->with('error', 'User not found.');
        }

        // Get the center related to the transaction
        $center = Center::find($transaction->cid);
        if (!$center) {
            return redirect()->back()->with('error', 'Center not found.');
        }

        // Save the original amount for logging purposes
        $originalAmount = $transaction->amount;

        // Update the transaction amount
        $transaction->amount = $request->amount;
        $transaction->save();

        // Insert a new record into the transactionlogs table
        TransactionLog::create([
            'tid' => $transaction->tid,
            'uid' => $user->uid, // Logged-in user ID
            'rid' => $transaction->rid,
            'cid' => $transaction->cid,
            'amount' => $request->amount,
            'remark' => 'Amount updated from ' . number_format($originalAmount, 2) . ' to ' . number_format($request->amount, 2),
            'action' => 'updated',
        ]);

        // Construct SMS message
        $message = $user->fname . ' ' . $user->lname . ' (' . $user->role . ') updated the amount from LKR ' . 
                   number_format($originalAmount, 2) . ' to LKR ' . number_format($request->amount, 2) .
                   ' for center: ' . $center->centername;

        // Insert a new SMS record into the `sms` table
        Sms::create([
            'tid' => $transaction->tid,
            'description' => $message,
            'phonenumber1' => $center->authorizedcontact,
            'phonenumber2' => $center->selectedcontact,
            'phonenumber3' => $center->thirdpartycontact,
            'phonenumber4' => '', // Include additional phone numbers as needed
            'phonenumber5' => '',
        ]);

        // Send SMS to authorized, selected, and third-party contacts
        $this->sendSms($center->authorizedcontact, $message);
        $this->sendSms($center->selectedcontact, $message);
        $this->sendSms($center->thirdpartycontact, $message);

        return redirect()->route('incharge.transactions.index')->with('success', 'Amount updated and SMS sent successfully.');
    }

}