<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InventoryIssue extends Model
{
    use HasFactory;

    protected $table = 'inventoryissues';

    protected $fillable = [
        'author_id',
        'room_id',
        'description',
        'status',
        'issued_at'
    ];
    protected $hidden = ['created_at', 'updated_at', 'deleted_at'];

    protected $casts = [
        'issued_at' => 'date',
    ];
}
