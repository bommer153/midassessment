<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class likedDog extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id','dog',
    ];

    public function myUser(){
        return $this->hasOne(User::class, 'id', 'user_id');
    }
}
