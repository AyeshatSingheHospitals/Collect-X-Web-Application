<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class Lab extends Model
{
    use HasFactory;

    protected $table = 'lab';

    protected $primaryKey = 'lid';

    protected $fillable = [
        'uid',
        'name',
        'address',
    
    ];

    protected $hidden = [
        'password',
    ];

    // //hashing password
    // public function setPasswordAttribute($value){
    //     $this->attribute['password'] = bcrypt($value);
    // }

    public function systemuser(){
        return $this->belongsTo(Systemuser::class, 'uid');
      }

    //   public function labassign(){
    //     return $this->hasMany(LabAssign::class, 'lid');
    // }

    public function labassign(){
        return $this->belongsTo(LabAssign::class, 'lid');
    }

  
}
