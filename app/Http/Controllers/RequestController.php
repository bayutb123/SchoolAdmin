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
        // get status id where name == 'Pengajuan Pembelian'
        $status = \App\Models\Status::where('name', 'Pengajuan Pembelian')->first();
        // get all inventories where request_id is null
        $inventory = \App\Models\Inventory::where('request_id', null)->get();

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
            // get status where it name == 'Menunggu Persetujuan'

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
                $inventory->request_status = $status->id;
                $inventory->save();
            }
        }

        

        return redirect()->route('request');
    }

    // detail
    public function detail($id) {
        $request = \App\Models\InventoryRequest::where('id', $id)->first();
        $status = \App\Models\Status::all();
        
        $request->statusName = $status->where('id', $request->status)->first()->name;
        $request->statusColor = $status->where('id', $request->status)->first()->color;
        $request->roomName = \App\Models\Room::where('id', $request->room_id)->first()->name;
        $request->author = \App\Models\User::where('id', $request->author_id)->first()->name;
        $inventories = \App\Models\Inventory::where('request_id', $id)->get();
        foreach ($inventories as $it) {
            $total_price = $it->price * $it->quantity;

            $it->room_name = \App\Models\Room::where('id', $it->room_id)->first()->name;
            $it->statusName = $status->where('id', $it->status)->first()->name;
            // jika quantity berisi .00, maka tidak perlu menampilkan koma
            if (strpos($it->quantity, '.00') !== false) {
                $it->quantity = number_format($it->quantity, 0, ',', '.');
            } else {
                $it->quantity = number_format($it->quantity, 2, ',', '.');
            }

            $it->price = number_format($it->price, 0, ',', '.');
            $it->total_price = number_format($total_price, 0, ',', '.');
            $it->statusColor = $status->where('id', $it->status)->first()->color;
        }
        $request->total_price = number_format($request->total_price, 0, ',', '.');

        $widget = [
            'request' => $request,
            'inventories' => $inventories,
        ];
        return view('request.detail', compact('widget'));
    }

    // approve
    public function approve(Request $request) {
        $validated = $request->validate([
            'request_id' => 'required|integer',
        ]);

        // get status id where name == 'Disetujui'
        $status = \App\Models\Status::where('name', 'Disetujui')->first();

        if ($validated) {
            $request = \App\Models\InventoryRequest::where('id', $validated['request_id'])->first();
            $request->status = 12;
            $request->save();

            $inventories = \App\Models\Inventory::where('request_id', $validated['request_id'])->get();
            foreach ($inventories as $it) {
                $it->request_status = $status->id;
                $it->save();
            }
        }
    
        return redirect()->route('request')->withSuccess('Request approved successfully.');
    }
}
