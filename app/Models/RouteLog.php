<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class RouteLog extends Model
{
    use HasFactory;

    protected $table = 'routelogs';

   

    protected $fillable = [
        'rid',
        'uid',
        'lid',
        'routename',
        'description',
        'action',
        
       
    ];

    //relationship to systemuser

  public function systemuser(){
    return $this->belongsTo(Systemuser::class, 'uid');
  }

  
  public function lab(){
    return $this->belongsTo(Lab::class, 'lid');
  }
}
