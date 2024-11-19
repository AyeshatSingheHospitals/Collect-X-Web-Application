<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class CenterLog extends Model
{
    use HasFactory;

    protected $table = 'centerlogs';

    
    protected $fillable = [
        'cid',
        'uid',
        'rid',
        'lid',
        'centername',
        'authorizedperson',
        'authorizedcontact',
        'selectedcontact',
        'thirdpartycontact',
        'description',
        'latitude',
        'longitude',
        'action',
    ];

    //relationship to systemuser

  public function systemuser(){
    return $this->belongsTo(Systemuser::class, 'uid');
  }

     //relationship to route

     public function route(){
        return $this->belongsTo(Route::class, 'rid');
      }

      public function lab(){
        return $this->belongsTo(Lab::class, 'lid');
      }
}