<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $table = 'transaction';

    protected $primaryKey = 'tid';

    protected $fillable = [
        'uid',
        'rid',
        'cid',
        'bill_amount',
        'amount',
        'difference_amount',
        'remark',
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
        return $this->hasMany(SMS::class, 'tid', 'tid'); // Correct relationship
    }
}
