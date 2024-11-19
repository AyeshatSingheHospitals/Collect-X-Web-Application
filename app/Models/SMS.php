<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


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
        
       
    ];

    //relationship to systemuser

  public function transaction(){
    return $this->belongsTo(Transaction::class, 'tid');
  }


}
