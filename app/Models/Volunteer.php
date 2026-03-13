<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Volunteer extends Model
{
    use HasFactory;

    protected $fillable = [
        'full_name',
        'gender',
        'date_of_birth',
        'joining_date',
        'phone',
        'email',
        'photo',
        'aadhaar_document',
        'status',
    ];
}