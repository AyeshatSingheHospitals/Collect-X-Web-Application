<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class LabAssignLog extends Model
{
    use HasFactory;

    protected $table = 'labassignlogs';

    protected $fillable = [
        'laid',
        'uid',
        'lid',
        'uid_assign',
        'action'
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
