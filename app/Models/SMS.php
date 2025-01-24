<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SMS extends Model
{
    use HasFactory;

    protected $table = 'sms';

    protected $primaryKey = 'sid';

    protected $fillable = [
        'tid',
        'description',
        'phonenumber1',
        'phonenumber2',
        'phonenumber3',
        'phonenumber4',
        'phonenumber5',
    ];

    // Relationship to Transaction
    public function transaction()
    {
        return $this->belongsTo(Transaction::class, 'tid', 'tid');
    }
}
