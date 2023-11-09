<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    use HasFactory;

    protected $table = 'rooms';

    protected $fillable = ['type', 'name', 'floor', 'size', 'size_unit', 'status', 'last_author_id'];

    protected $hidden = ['created_at', 'updated_at', 'deleted_at'];
    
}
