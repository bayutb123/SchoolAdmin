<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RequestController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index() {
        // if user is admin, show all requests
        if (Auth::user()->role_id == 1) {
            $request = \App\Models\InventoryRequest::all()->reverse();
        } else {
            $request = \App\Models\InventoryRequest::where('author_id', Auth::user()->id)->get()->reverse();
        }
        $status = \App\Models\Status::all();
        foreach ($request as $it) {
            $room = \App\Models\Room::where('id', $it->room_id)->first();
            $it->room = $room->name;
            $it->statusColor = $status->where('id', $it->status)->first()->color;
            $user = \App\Models\User::where('id', $it->author_id)->first();
            $it->author_id = $user->name;
            
            // add total price and format it to IDR
            $it->total_price = number_format($it->total_price, 0, ',', '.');
        }

        $widget = [
            'request' => $request,
        ];
        return view('request', compact('widget'));
    }

    public function create() {
        // rooms
        $rooms = \App\Models\Room::all();
        // status 
        $status = \App\Models\Status::all();
        // inventory where status == 10
        $inventory = \App\Models\Inventory::where('status', 10)->get();

        // change each inventory to include room name
        foreach ($inventory as $it) {
            $room = \App\Models\Room::where('id', $it->room_id)->first();
            $it->room_id = $room->name;
            $it->statusName = $status->where('id', $it->status)->first()->name;
        }
        
        $widget = [
            'rooms' => $rooms,
            'inventories' => $inventory,
        ];
        return view('request.add' , compact('widget'));
    }

    public function store(Request $request) {

        $validated = $request->validate([
            'author_id' => 'required|integer',
            'room_id' => 'required|integer',
            'inventories' => 'required|array',
            'description' => 'required|string',
        ]);

        if ($validated) {
            // get status where it name == 'Pengajuan Pembelian'
            $status = \App\Models\Status::where('name', 'Pengajuan Pembelian')->first();

            $request = new \App\Models\InventoryRequest;
            $request->author_id = $validated['author_id'];
            $request->room_id = $validated['room_id'];
            $request->description = $validated['description'];
            // add to total_price from each inventory
            $total_price = 0;
            foreach ($validated['inventories'] as $it) {
                $inventory = \App\Models\Inventory::where('id', $it)->first();
                $total_price += $inventory->price * $inventory->quantity;
            }
            $request->total_price = $total_price;
            $request->status = $status->id;
            $request->save();

            foreach ($validated['inventories'] as $it) {
                $inventory = \App\Models\Inventory::where('id', $it)->first();
                $inventory->request_id = $request->id;
                $inventory->status = $status->id;
                $inventory->save();
            }
        }

        

        return redirect()->route('request');
    }

}
