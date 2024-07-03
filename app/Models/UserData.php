<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserData extends Model
{
    use HasFactory;

    public $timestamps = true;

    protected $table = 'user_data';

    protected $fillable = [
        'first_name',
        'last_name',
        'age',
        'gender',
        'mobile_number',
        'email',
        'city',
        'login',
        'car_model',
        'salary'
    ];
}
