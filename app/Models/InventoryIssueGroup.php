<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InventoryIssueGroup extends Model
{
    use HasFactory;

    protected $fillable = [
        'inventory_group_id',
        'inventory_id',
        'description',
        'solution',
        'status',
        'done_at',
        'note',
    ];

    protected $hidden = ['created_at', 'updated_at', 'deleted_at'];

    protected $casts = [
        'done_at' => 'date',
    ];
}
