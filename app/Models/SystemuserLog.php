<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Foundation\Auth\User as Authenticatable; // Import this class
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User;
// use Illuminate\Contracts\Auth\Authenticatable;

class SystemuserLog extends Authenticatable
{
    use HasFactory;

    protected $table = 'systemuserlogs';

        protected $fillable = [
        'logged_uid',
        'uid',
        'role',
        'fname',
        'lname',
        'contact',
        'epf',
        'username',
        'password',
        'status',
        'image',
        'action',
    ];

    protected $hidden = [
        'password',
    ];

    // Hash the password automatically
    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = bcrypt($value);
    }

    // // Full name accessor
    // public function getFullNameAttribute()
    // {
    //     return $this->fname . ' ' . $this->lname;
    // }

    // // Image URL accessor
    // public function getImageUrlAttribute()
    // {
    //     return $this->image ? asset('storage/' . $this->image) : null;
    // }

    // public function labassigns()
    // {
    //     return $this->hasMany(LabAssign::class, 'uid')->withDefault([
    //         'name' => 'No lab assigned',
    //     ]);
    // }
}
