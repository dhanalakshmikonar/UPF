<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sponsor extends Model
{
    use HasFactory;

    protected $fillable = [
        'serial_no',
        'name',
        'age',
        'gender',
        'category',
        'phone',
        'email',
        'address',
        'home',
        'aadhaar_number',
        'contact_number',
        'remarks',
        'date_of_birth',
        'date_of_joining',
        'amount_donated',
        'donation_date',
        'photo',
        'aadhaar_document'
    ];
}
