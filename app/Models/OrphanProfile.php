<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrphanProfile extends Model
{
    use HasFactory;

    protected $fillable = [
        'orphan_id',
        'background_history',
        'disability_type',
        'disability_details',
        'special_care_required',
        'blood_group',
        'medical_conditions',
        'education_status',
        'school_name',
        'current_class',
        'aadhaar_document',
        'photo',
    ];

    public function orphan()
    {
        return $this->belongsTo(Orphan::class);
    }
}
