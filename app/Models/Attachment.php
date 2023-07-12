<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attachment extends Model
{
    use HasFactory;

    protected $fillable = [
        'tweet_id', 
        'media',
     ];

     public function tweet()
     {
        return $this->belongsTo(Tweet::class);
     }
}
