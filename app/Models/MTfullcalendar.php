<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MTfullcalendar extends Model
{
    use HasFactory;
    public $timestamps = true;
     protected $fillable = ['summary','description','start','end','created_at','updated_at'];
}
