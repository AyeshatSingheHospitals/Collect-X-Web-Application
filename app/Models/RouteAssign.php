<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class RouteAssign extends Model
{
    use HasFactory;

    protected $table = 'routeassign';

    protected $primaryKey = 'raid';

    protected $fillable = [
        'uid',
        'rid',
        'uid_ro',
    ];

    //relationship to systemuser

    public function systemuser(){
        return $this->belongsTo(Systemuser::class, 'uid_ro', 'uid');
      }

    //relationship to route

     public function route(){
        return $this->belongsTo(Route::class, 'rid');
      }

}