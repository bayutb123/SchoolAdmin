<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Inventory;
use App\Http\Requests\AddInitialInventoryRequest;

class InventoryController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $inventory = \App\Models\Inventory::all();

        foreach ($inventory as $it) {
            $room = \App\Models\Room::where('id', $it->room_id)->first();
            $it->room = $room->name;
            if ($it->status == 'A') {
                $it->status = '<span class="badge badge-success">Baik</span>';
            } else if ($it->status == 'B') {
                $it->status = '<span class="badge badge-warning">Kurang</span>';
            } else if ($it->status == 'C') {
                $it->status = '<span class="badge badge-danger">Tidak Layak</span>';
            } else {
                $it->status = '<span class="badge badge-secondary">Tidak diketahui</span>';
            }
            $user = \App\Models\User::where('id', $it->last_author_id)->first();
            $it->last_author_id = $user->name;
        }

        $widget = [
            'inventory' => $inventory,
        ];
        
        return view('inventory', compact('widget'));
    }

    public function create()
    {
        $rooms = \App\Models\Room::all();
        $widget = [
            'rooms' => $rooms
        ];
        return view('add.initialinven' , compact('widget'));
    }

    public function store(AddInitialInventoryRequest $request) {
        $validated = $request->validated();

        if ($validated) {
            $inventory = Inventory::create(
                [
                    'room_id' => $request->room_id,
                    'category' => $request->category,
                    'name' => $request->name,
                    'description' => $request->description,
                    'quantity' => $request->quantity,
                    'quantity_unit' => $request->quantity_unit,
                    'status' => $request->status,
                    'last_author_id' => $request->last_author_id,
                ]
            );
        }
        return redirect()->route('inventory.create')->withSuccess('Inventory added successfully.');
    }
}
