<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class LabAssign extends Model
{
    use HasFactory;

    protected $table = 'labassign';

    protected $primaryKey = 'laid';

    protected $fillable = [
    
        'uid',
        'lid',
        'uid_assign'
    ];

  
    //relationship to systemuser

    public function systemuser(){
        return $this->belongsTo(Systemuser::class, 'uid');
      }

    //relationship to lab

     public function lab(){
        return $this->belongsTo(Lab::class, 'lid');
      }

}
