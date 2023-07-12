<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tweet extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_account_handle', 
        'text',
     ];

     public function user()
     {
        return $this->belongsTo(User::class, 'user_account_id');
     }
}
