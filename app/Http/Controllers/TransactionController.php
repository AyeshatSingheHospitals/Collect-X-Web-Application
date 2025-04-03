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

use Illuminate\Support\Facades\DB;

use Playsms\Webservices;

class TransactionController extends Controller
{
    // For admin 
    
    public function index()
    {
        $transactions = Transaction::with([
                'systemuser', 
                'center', 
                'sms' => function($query) {
                    $query->whereNotNull('description'); // Only load SMS with descriptions
                }
            ])
            ->get();
        
        return view('Admin.transaction', compact('transactions'));
    }

    public function updateAmount(Request $request, $tid)
    {
        // Validate request
        $request->validate([
            'amount' => 'required|numeric',
            'bill_amount' => 'required|numeric',
            'difference_amount' => 'required|numeric'
        ]);
    
        // Start database transaction
        DB::beginTransaction();
    
        try {
            // Find the transaction
            $transaction = Transaction::findOrFail($tid);
            $user = Systemuser::findOrFail(session('uid'));
            $center = Center::findOrFail($transaction->cid);
    
            // Check if amounts changed
            if ($transaction->amount == $request->amount && 
                $transaction->bill_amount == $request->bill_amount) {
                return redirect()->back()->with('info', 'No changes made.');
            }
    
            // Save original values
            $originalAmount = $transaction->amount;
            $originalBillAmount = $transaction->bill_amount;
            $originalDifferenceAmount = $transaction->difference_amount;
    
            // Update transaction
            $transaction->update([
                'amount' => $request->amount,
                'bill_amount' => $request->bill_amount,
                'difference_amount' => $request->difference_amount
            ]);
    
            // Create transaction log
            $remark = "Bill Amount: LKR " . number_format($originalBillAmount, 2) . " → " . 
                     number_format($request->bill_amount, 2) . "\n" .
                     "Hand Over: LKR " . number_format($originalAmount, 2) . " → " . 
                     number_format($request->amount, 2) . "\n" .
                     "Difference: LKR " . number_format($originalDifferenceAmount, 2) . " → " . 
                     number_format($request->difference_amount, 2);
    
            TransactionLog::create([
                'tid' => $transaction->tid,
                'uid' => $user->uid,
                'rid' => $transaction->rid,
                'cid' => $transaction->cid,
                'bill_amount' => $request->bill_amount,
                'amount' => $request->amount,
                'difference_amount' => $request->difference_amount,
                'remark' => $remark,
                'action' => 'updated',
            ]);
    
            // Prepare SMS message in the new format
            $message ="User: " . $user->fname . " " . $user->lname . " - " .
               "Bill Amount: LKR " . number_format($originalBillAmount, 2) . " to " . number_format($request->bill_amount, 2) . " - " .
               "Hand Over Amount: LKR " . number_format($originalAmount, 2) . " to " . number_format($request->amount, 2) . " - " .
               "Difference Amount: LKR " . number_format($originalDifferenceAmount, 2) . " to " . number_format($differenceAmount, 2) . " - " .
               "Center: " . $center->centername;
    
            // Create SMS record
            $sms = Sms::create([
                'tid' => $transaction->tid,
                'description' => $message,
                'phonenumber1' => $center->authorizedcontact,
                'phonenumber2' => $center->selectedcontact,
                'phonenumber3' => $center->thirdpartycontact,
            ]);
    
            // Get all valid phone numbers
            $phoneNumbers = array_filter([
                $center->authorizedcontact,
                $center->selectedcontact,
                $center->thirdpartycontact
            ]);
    
            // Send SMS (single API call for all numbers)
            if (!empty($phoneNumbers)) {
                $smsSent = $this->sendSms($phoneNumbers, $message);
                
                if (!$smsSent) {
                    throw new \Exception('Failed to send SMS notifications');
                }
            }
    
            DB::commit();
            
            return redirect()->route('transactions.index')
                   ->with('success', 'Amount updated and notifications sent successfully.');
            
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                   ->with('error', 'Error updating amount: ' . $e->getMessage());
        }
    }

    private function sendSms($phoneNumbers, $message)
    {
        if (empty($phoneNumbers) || empty($message)) {
            \Log::warning('SMS not sent - empty phone numbers or message');
            return false;
        }

        // Ensure numbers are in array format and valid
        $numbers = is_array($phoneNumbers) ? $phoneNumbers : [$phoneNumbers];
        $validNumbers = array_filter($numbers, function($num) {
            return !empty($num) && is_numeric($num) && strlen($num) >= 9;
        });

        if (empty($validNumbers)) {
            \Log::warning('No valid phone numbers provided for SMS');
            return false;
        }

        $numberList = implode(',', $validNumbers);
        
        // Configure SMS API
        $apiKey = config('services.sms.api_key'); // Better to store in config
        $sourceAddress = config('services.sms.sender_id');
        $apiUrl = 'https://e-sms.dialog.lk/api/v1/message-via-url/create/url-campaign';
        
        try {
            $url = $apiUrl . '?' . http_build_query([
                'esmsqk' => $apiKey,
                'list' => $numberList,
                'source_address' => $sourceAddress,
                'message' => $message
            ]);

            $ch = curl_init();
            curl_setopt_array($ch, [
                CURLOPT_URL => $url,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_TIMEOUT => 10, // 10 second timeout
                CURLOPT_SSL_VERIFYPEER => config('app.env') === 'production' // Verify SSL in production
            ]);

            $response = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            
            if (curl_errno($ch)) {
                throw new \Exception('cURL error: ' . curl_error($ch));
            }

            curl_close($ch);

            if ($httpCode == 200) {
                \Log::info("SMS sent to {$numberList}", ['response' => $response]);
                return true;
            }

            throw new \Exception("HTTP {$httpCode} - " . $response);
            
        } catch (\Exception $e) {
            \Log::error("SMS failed to {$numberList}", [
                'error' => $e->getMessage(),
                'message' => $message
            ]);
            return false;
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

    // Manually load SMS records with phone numbers
    $formattedTransactions = $transactions->map(function ($transaction) {
        // Fetch SMS records with all needed fields
        $smsRecords = SMS::where('tid', $transaction->tid)
            ->get()
            ->map(function ($sms) {
                return [
                    'description' => $sms->description,
                    'phonenumber1' => $sms->phonenumber1,
                    'phonenumber2' => $sms->phonenumber2,
                    'phonenumber3' => $sms->phonenumber3
                ];
            });

        return [
            'tid' => $transaction->tid,
            'date' => $transaction->created_at->format('Y-m-d H:i:s'),
            'full_name' => optional($transaction->systemuser)->fname . ' ' . optional($transaction->systemuser)->lname ?? 'N/A',
            'center_name' => optional($transaction->center)->centername ?? 'N/A',
            'bill_amount' => $transaction->bill_amount, 
            'amount' => $transaction->amount, 
            'difference_amount' => $transaction->difference_amount,
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
                'bill_amount' => number_format($transaction->bill_amount, 3),
                'amount' => number_format($transaction->amount, 3),
                'difference_amount' => number_format($transaction->difference_amount, 3), // Ensures 3 decimal places
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
        'bill_amount' => 'required|numeric|min:0',
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

    // Check if any values have changed
    $billAmountChanged = $transaction->bill_amount != $request->bill_amount;
    $amountChanged = $transaction->amount != $request->amount;
    
    if (!$billAmountChanged && !$amountChanged) {
        return response()->json(['success' => false, 'message' => 'No changes detected.'], 400);
    }

    // Save the original values for logging purposes
    $originalBillAmount = $transaction->bill_amount;
    $originalAmount = $transaction->amount;
    $originalDifferenceAmount = $transaction->difference_amount;

    // Calculate the new difference amount
    $differenceAmount = $request->amount - $request->bill_amount;

    // Update the transaction
    $transaction->bill_amount = $request->bill_amount;
    $transaction->amount = $request->amount;
    $transaction->difference_amount = $differenceAmount;
    $transaction->save();

    // Insert a new record into the transactionlogs table
    TransactionLog::create([
        'tid' => $transaction->tid,
        'uid' => $user->uid,
        'rid' => $transaction->rid,
        'cid' => $transaction->cid,
        'bill_amount' => $request->bill_amount,
        'amount' => $request->amount,
        'difference_amount' => $differenceAmount,
        'remark' => "Bill Amount: LKR " . number_format($originalBillAmount, 2) . " to " . number_format($request->bill_amount, 2) . " - " .
                    "Hand Over Amount: LKR " . number_format($originalAmount, 2) . " to " . number_format($request->amount, 2) . " - " .
                    "Difference Amount: LKR " . number_format($originalDifferenceAmount, 2) . " to " . number_format($differenceAmount, 2),
        'action' => 'updated',
    ]);

    // Construct SMS message
    $message = "User: " . $user->fname . " " . $user->lname . " - " .
               "Bill Amount: LKR " . number_format($originalBillAmount, 2) . " to " . number_format($request->bill_amount, 2) . " - " .
               "Hand Over Amount: LKR " . number_format($originalAmount, 2) . " to " . number_format($request->amount, 2) . " - " .
               "Difference Amount: LKR " . number_format($originalDifferenceAmount, 2) . " to " . number_format($differenceAmount, 2) . " - " .
               "Center: " . $center->centername;

    // Insert a new SMS record into the `sms` table
    Sms::create([
        'tid' => $transaction->tid,
        'description' => $message,
        'phonenumber1' => $center->authorizedcontact,
        'phonenumber2' => $center->selectedcontact,
        'phonenumber3' => $center->thirdpartycontact,
       
    ]);

    // Send SMS to authorized, selected, and third-party contacts
    $this->sendSms($center->authorizedcontact, $message);
    $this->sendSms($center->selectedcontact, $message);
    $this->sendSms($center->thirdpartycontact, $message);

    // Return JSON response for AJAX
    return response()->json([
        'success' => true,
        'message' => 'Amounts updated and SMS sent successfully.',
        'new_difference_amount' => number_format($differenceAmount, 2)
    ]);
}


    // --------------------------Incharge transaction-------------------------

    public function indexIncharge(Request $request)
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

        return view('incharge.transaction', compact('formattedTransactions', 'assignedLabs'));
    }

public function updateAmountIncharge(Request $request, $tid)
{
    // Validate the request
    $request->validate([
        'bill_amount' => 'required|numeric|min:0',
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

    // Check if any values have changed
    $billAmountChanged = $transaction->bill_amount != $request->bill_amount;
    $amountChanged = $transaction->amount != $request->amount;
    
    if (!$billAmountChanged && !$amountChanged) {
        return response()->json(['success' => false, 'message' => 'No changes detected.'], 400);
    }

    // Save the original values for logging purposes
    $originalBillAmount = $transaction->bill_amount;
    $originalAmount = $transaction->amount;
    $originalDifferenceAmount = $transaction->difference_amount;

    // Calculate the new difference amount
    $differenceAmount = $request->amount - $request->bill_amount;

    // Update the transaction
    $transaction->bill_amount = $request->bill_amount;
    $transaction->amount = $request->amount;
    $transaction->difference_amount = $differenceAmount;
    $transaction->save();

    // Insert a new record into the transactionlogs table
    TransactionLog::create([
        'tid' => $transaction->tid,
        'uid' => $user->uid,
        'rid' => $transaction->rid,
        'cid' => $transaction->cid,
        'bill_amount' => $request->bill_amount,
        'amount' => $request->amount,
        'difference_amount' => $differenceAmount,
        'remark' => "Bill Amount: LKR " . number_format($originalBillAmount, 2) . " to " . number_format($request->bill_amount, 2) . " - " .
                    "Hand Over Amount: LKR " . number_format($originalAmount, 2) . " to " . number_format($request->amount, 2) . " - " .
                    "Difference Amount: LKR " . number_format($originalDifferenceAmount, 2) . " to " . number_format($differenceAmount, 2),
        'action' => 'updated',
    ]);

    // Construct SMS message
    $message = "User: " . $user->fname . " " . $user->lname . " - " .
               "Bill Amount: LKR " . number_format($originalBillAmount, 2) . " to " . number_format($request->bill_amount, 2) . " - " .
               "Hand Over Amount: LKR " . number_format($originalAmount, 2) . " to " . number_format($request->amount, 2) . " - " .
               "Difference Amount: LKR " . number_format($originalDifferenceAmount, 2) . " to " . number_format($differenceAmount, 2) . " - " .
               "Center: " . $center->centername;

    // Insert a new SMS record into the `sms` table
    Sms::create([
        'tid' => $transaction->tid,
        'description' => $message,
        'phonenumber1' => $center->authorizedcontact,
        'phonenumber2' => $center->selectedcontact,
        'phonenumber3' => $center->thirdpartycontact,
        
    ]);

    // Send SMS to authorized, selected, and third-party contacts
    $this->sendSms($center->authorizedcontact, $message);
    $this->sendSms($center->selectedcontact, $message);
    $this->sendSms($center->thirdpartycontact, $message);

    // Return JSON response for AJAX
    return response()->json([
        'success' => true,
        'message' => 'Amounts updated and SMS sent successfully.',
        'new_difference_amount' => number_format($differenceAmount, 2)
    ]);
}
}