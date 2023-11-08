<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InventoryRequestGroup extends Model
{
    use HasFactory;

    protected $table = 'inventoryrequestgroups';

    protected $fillable = [
        'inventory_group_id',
        'name',
        'category',
        'quantity',
        'quantity_unit',
        'price',
        'total',
        'status',
        'purchased_at',
        'note',
    ];

    protected $hidden = ['created_at', 'updated_at', 'deleted_at'];

    protected $casts = [
        'purchased_at' => 'date',
    ];
}
