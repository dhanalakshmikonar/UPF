<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HomeDocumentRecord extends Model
{
    use HasFactory;

    protected $fillable = [
        'home_document_id',
        'category',
        'data',
    ];

    protected $casts = [
        'data' => 'array',
    ];

    public function document()
    {
        return $this->belongsTo(HomeDocument::class, 'home_document_id');
    }
}
