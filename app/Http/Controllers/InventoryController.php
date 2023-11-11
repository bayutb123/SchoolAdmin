<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Inventory;
use App\Http\Requests\AddInitialInventoryRequest;
use App\Http\Requests\InventoryRequestRequest;

class InventoryController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $inventory = \App\Models\Inventory::all();
        $status = \App\Models\Status::all();
        foreach ($inventory as $it) {
            $room = \App\Models\Room::where('id', $it->room_id)->first();
            
            $it->room = $room->name;
            $it->statusName = $status->where('id', $it->status)->first()->name;
            $it->statusColor = $status->where('id', $it->status)->first()->color;

            if ($it->issue_status == 5 || $it->issue_status == 6) {
                $it->isIssued = true;
                $it->issueStatusName = $status->where('id', $it->issue_status)->first()->name;
                $it->issueStatusColor = $status->where('id', $it->issue_status)->first()->color;
            } else if ($it->request_id != null) {
                $it->isRequested = true;
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
        $category = Inventory::select('category')->distinct()->get();
        $status = \App\Models\Status ::where('type', 'inventory')->get();
        $widget = [
            'rooms' => $rooms,
            'status' => $status,
            'category' => $category,
        ];
        return view('inven.add' , compact('widget'));
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
        $status = \App\Models\Status::where('type', 'inventory')->get();
        $rooms = \App\Models\Room::all();
        $widget = [
            'inventory' => $inventory,
            'rooms' => $rooms,
            'category' => $category,
            'status' => $status,
        ];
        return view('inven.edit', compact('widget'));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'inventory_id' => 'required',
            'name' => 'required',
            'room_id' => 'required',
            'category' => 'required',
            'description' => 'nullable',
            'quantity' => 'required',
            'status' => 'required',
            'last_author_id' => 'required',
        ]);

        if ($validated) {
            $inventory = Inventory::where('id', $validated['inventory_id'])->update(
                [
                    'name' => $request->name,
                    'room_id' => $request->room_id,
                    'category' => $request->category,
                    'description' => $request->description,
                    'quantity' => $request->quantity,
                    'status' => $request->status,
                    'last_author_id' => $request->last_author_id,
                ]
            );
        }
        return redirect()->route('inventory')->withSuccess('Inventory updated successfully.');
    }

    public function request() {
        $rooms = \App\Models\Room::all();
        $status = \App\Models\Status::where('name', 'Rencana Pembelian')->get();

        $widget = [
            'rooms' => $rooms,
            'status' => $status,
        ];

        return view('inven.request' , compact('widget'));
    }

    public function requestStore(InventoryRequestRequest $request) {
        $validated = $request->validated();

        if ($validated) {
            $inventory = Inventory::create(
                [
                    'room_id' => $request->room_id,
                    'category' => $request->category,
                    'name' => $request->name,
                    'description' => $request->description,
                    'price' => $request->price,
                    'quantity' => $request->quantity,
                    'quantity_unit' => $request->quantity_unit,
                    'status' => $request->status,
                    'last_author_id' => $request->last_author_id,
                ]
            );
        }
        return redirect()->route('inventory.request')->withSuccess('Request added successfully.');
    }

    public function requestEdit($id) {
        $inventory = Inventory::where('id', $id)->first();
        $rooms = \App\Models\Room::all();
        $status = \App\Models\Status::all();
        $inventory->statusName = $status->where('id', $inventory->status)->first()->name;
        $widget = [
            'inventory' => $inventory,
            'rooms' => $rooms,
            'status' => $status,
        ];
        return view('inven.editrequest', compact('widget'));
    }

    public function requestUpdate(Request $request) {
        $validated = $request->validate([
            'inventory_id' => 'required',
            'name' => 'required',
            'room_id' => 'required',
            'category' => 'required',
            'description' => 'nullable',
            'price' => 'required',
            'quantity' => 'required',
            'status' => 'required',
            'last_author_id' => 'required',
        ]);

        if ($validated) {
            $inventory = Inventory::where('id', $validated['inventory_id'])->update(
                [
                    'name' => $request->name,
                    'room_id' => $request->room_id,
                    'category' => $request->category,
                    'description' => $request->description,
                    'price' => $request->price,
                    'quantity' => $request->quantity,
                    'status' => $request->status,
                    'last_author_id' => $request->last_author_id,
                ]
            );
        }
        return redirect()->route('inventory')->withSuccess('Inventory updated successfully.');
    }
}
