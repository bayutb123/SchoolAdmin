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
        
        return view('inventory');
    }

    public function create()
    {
        $userid = auth()->user()->id;
        return view('add.initialinven' , compact('userid'));
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
        return redirect()->route('inventory.index')->withSuccess('Inventory added successfully.');
    }
}
