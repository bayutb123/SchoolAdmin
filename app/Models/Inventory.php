<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inventory extends Model
{
    use HasFactory;

    protected $table = 'inventories';

    protected $fillable = [
        'room_id', 
        'category', 
        'name', 
        'description', 
        'quantity', 
        'quantity_unit', 
        'status', 
        'last_author_id'
    ];

    protected $hidden = ['created_at', 'updated_at', 'deleted_at'];

    protected $casts = [
        'purchased_at' => 'date',
    ];
}
