<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Systemuser extends Model
{
    use HasFactory;

    protected $table = 'systemuser';

    protected $primaryKey = 'uid';

    protected $fillable = [
        'role',
        'fname',
        'lname',
        'contact',
        'epf',
        'username',
        'password',
        'status',
        'image'
    ];

    protected $hidden = [
        'password',
    ];

    // //hashing password
    // public function setPasswordAttribute($value){
    //     $this->attribute['password'] = bcrypt($value);
    // }

    
}
