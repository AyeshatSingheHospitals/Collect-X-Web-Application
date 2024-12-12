<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class Route extends Model
{
    use HasFactory;

    protected $table = 'route';

    protected $primaryKey = 'rid';

    protected $fillable = [
        'uid',
        'lid',
        'routename',
        'description', 
    ];

    //relationship to systemuser

  public function systemuser(){
    return $this->belongsTo(Systemuser::class, 'uid');
  }

  public function lab(){
    return $this->belongsTo(Lab::class, 'lid');
  }
}
