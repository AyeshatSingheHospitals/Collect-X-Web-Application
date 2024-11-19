<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class RouteAssignLog extends Model
{
    use HasFactory;

    protected $table = 'routeassignlogs';


    protected $fillable = [
        'raid',
        'uid',
        'rid',
        'uid_ro',
    ];

  
    //relationship to systemuser

    public function systemuser(){
        return $this->belongsTo(Systemuser::class, 'uid');
      }

    //relationship to route

     public function route(){
        return $this->belongsTo(Route::class, 'rid');
      }
}
