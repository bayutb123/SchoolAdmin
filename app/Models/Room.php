<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    use HasFactory;

    protected $fillable = ['type', 'name', 'floor', 'size', 'size_unit'];

    protected $hidden = ['created_at', 'updated_at', 'deleted_at'];
    
}