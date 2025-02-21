<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Google extends Model
{
    use HasFactory;
    protected $fillable = [
        'detected_text', 'file_path'
    ];
    protected $table = 'google_results';
}
