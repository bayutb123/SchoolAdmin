<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Environment extends Model
{
    use HasFactory;

    protected $table = 'environments';

    protected $fillable = ['category', 'name', 'description', 'status', 'last_author_id'];

    protected $hidden = ['created_at', 'updated_at', 'deleted_at'];

}
