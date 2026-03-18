<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Orphan extends Model
{
    use HasFactory;

    protected $fillable = [
        'serial_no',
        'full_name',
        'age',
        'gender',
        'category',
        'address',
        'home',
        'aadhaar_number',
        'contact_number',
        'remarks',
        'date_of_birth',
        'admission_date',
        'photo',
        'status',
        'aadhaar_document'
    ];

    public function profile()
    {
        return $this->hasOne(OrphanProfile::class);
    }
}
