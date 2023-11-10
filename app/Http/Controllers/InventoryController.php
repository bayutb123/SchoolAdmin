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
            if ($it->status == 'Baik') {
                $it->status = '<span class="badge badge-success p-2">' . $it->status . '</span>';
            } else if ($it->status == 'Kurang') {
                $it->status = '<span class="badge badge-warning p-2">' . $it->status . '</span>';
            } else if ($it->status == 'Tidak layak') {
                $it->status = '<span class="badge badge-danger p-2">' . $it->status . '</span>';
            } else {
                $it->status = '<span class="badge badge-secondary p-2">' . $it->status . '</span>';
                $it->isIssued = true;
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

    public function edit($id)
    {
        $inventory = Inventory::where('id', $id)->first();
        // get all category from all inventory
        $category = Inventory::select('category')->distinct()->get();
        $status = Inventory::select('status')->distinct()->get();
        $rooms = \App\Models\Room::all();
        $widget = [
            'inventory' => $inventory,
            'rooms' => $rooms,
            'category' => $category,
            'status' => $status,
        ];
        return view('edit.initialinven', compact('widget'));
    }
}
