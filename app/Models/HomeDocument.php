<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HomeDocument extends Model
{
    use HasFactory;

    protected $fillable = [
        'category',
        'file_path',
        'original_name',
    ];
}
