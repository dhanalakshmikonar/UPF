<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Orphan extends Model
{
    use HasFactory;

    protected $fillable = [
        'full_name',
        'gender',
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
