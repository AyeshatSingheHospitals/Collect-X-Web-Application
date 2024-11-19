<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class LabLog extends Model
{
    use HasFactory;

    protected $table = 'lablogs';

    protected $fillable = [
        'lid',
        'uid',
        'name',
        'address',
        'action',
        
       
    ];


    public function systemuser(){
        return $this->belongsTo(Systemuser::class, 'uid');
      }
}
