<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InventoryRequest extends Model
{
    use HasFactory;

    protected $table = 'inventoryrequests';

    protected $fillable = [
        'author_id',
        'room_id',
        'description',
        'status',
        'requested_at'
    ];

    protected $hidden = ['created_at', 'updated_at', 'deleted_at'];

    protected $casts = [
        'requested_at' => 'date',
    ];
}
