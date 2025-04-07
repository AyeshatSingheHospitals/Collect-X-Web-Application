<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TxnImage extends Model
{
    use HasFactory;

    protected $table = 'txnimage';

    protected $primaryKey = 'txnid';

    protected $fillable = [
        'tid',      
        'bill_image',
    ];

    public function transaction()
    {
        return $this->belongsTo(Transaction::class, 'tid');
    }

}
