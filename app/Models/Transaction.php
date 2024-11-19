<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class Transaction extends Model
{
    use HasFactory;

    protected $table = 'tranaction';

    protected $primaryKey = 'tid';

    protected $fillable = [
       
        'uid',
        'rid',
        'cid',
        'amount',
        'remark',
    ];


    //relationship to systemuser

    public function systemuser(){
        return $this->belongsTo(Systemuser::class, 'uid');
      }

      
    //relationship to center

  public function center(){
    return $this->belongsTo(Center::class, 'cid');
  }

     
    //relationship to route

    public function route(){
      return $this->belongsTo(Route::class, 'rid');
    }
  


}
