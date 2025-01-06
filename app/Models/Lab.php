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

    public function systemuser()
    {
        return $this->belongsTo(Systemuser::class, 'uid')->withDefault([
            'fname' => 'No',
            'lname' => 'User Assigned',
        ]);
    }

    public function labassign(){
        return $this->belongsTo(LabAssign::class, 'lid');
    }
}
