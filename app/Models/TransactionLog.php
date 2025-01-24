<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TransactionLog extends Model
{
    use HasFactory;

    protected $table = 'transactionlogs';

    protected $fillable = [
        'tid',
        'uid',
        'rid',
        'cid',
        'amount',
        'remark',
        'action',
    ];

    // Relationship to Systemuser
    public function systemuser()
    {
        return $this->belongsTo(Systemuser::class, 'uid');
    }

    // Relationship to Center
    public function center()
    {
        return $this->belongsTo(Center::class, 'cid');
    }

    // Relationship to Route
    public function route()
    {
        return $this->belongsTo(Route::class, 'rid');
    }

    // Relationship to SMS
    public function sms()
    {
        return $this->hasOne(SMS::class, 'tid', 'tid'); // Correct relationship
    }
}
