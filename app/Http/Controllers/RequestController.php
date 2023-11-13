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
        $inventory = \App\Models\Inventory::where('request_id', null)->get()->reverse();

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

    // edit
    public function edit($id) {
        $request = \App\Models\InventoryRequest::where('id', $id)->first();
        $rooms = \App\Models\Room::all();
        $status = \App\Models\Status::all();
        $request->room_name = $rooms->where('id', $request->room_id)->first()->name;
        // author
        $request->author = \App\Models\User::where('id', $request->author_id)->first()->name;

        // get status id where name == 'Pengajuan Pembelian'
        $requestStatus = \App\Models\Status::where('name', 'Pengajuan Pembelian')->first();

        $allInventories = \App\Models\Inventory::where('request_id', null)->get();
        $inventories = \App\Models\Inventory::where('request_id', $id)->get();

        // add inventory to allInventories
        $allInventories = $allInventories->merge($inventories)->reverse();

        foreach ($inventories as $inventory) {
            $inventory->room_name = $rooms->where('id', $inventory->room_id)->first()->name;
            $inventory->condition = $status->where('id', $inventory->status)->first()->name;
        }

        foreach ($allInventories as $it) {
            $it->roomName = $rooms->where('id', $it->room_id)->first()->name;
            $it->statusName = $status->where('id', $it->status)->first()->name;
            $it->statusColor = $status->where('id', $it->status)->first()->color;
        }

        $status = \App\Models\Status::where('type', 'request')->get();
        $request->statusName = $status->where('id', $request->status)->first()->name;
        $request->statusColor = $status->where('id', $request->status)->first()->color;

        
        $widget = [
            'request' => $request,
            'inventories' => $inventories,
            'rooms' => $rooms,
            'status' => $status,
            'allInventories' => $allInventories,
        ];
        return view('request.edit', compact('widget'));
        

        $widget = [
            'rooms' => $rooms,
            'allInventories' => $inventory,
            'request' => $request,
            'inventories' => $inventories,
        ];
        return view('request.edit', compact('widget'));
    }

    // update
    public function update(Request $request) {
        $validate = [
            'request_id' => 'required|integer',
            'author_id' => 'required|integer',
            'room_id' => 'required|integer',
            'inventories' => 'required|array',
            'description' => 'required|string',
        ];

        $validated = $request->validate($validate);

        if ($validated) {

            // get status id where name == 'Rencaan Pembelian'
            $requestStatusPlanning = \App\Models\Status::where('name', 'Rencana Pembelian')->first();
            // get status id where name == 'Pengajuan Pembelian'
            $requestStatusProposal = \App\Models\Status::where('name', 'Pengajuan Pembelian')->first();

            // get the request 
            $request = \App\Models\InventoryRequest::where('id', $validated['request_id'])->first();
            $request->author_id = $validated['author_id'];
            $request->room_id = $validated['room_id'];
            $request->description = $validated['description'];

            // process inventories
            $inventories = \App\Models\Inventory::where('request_id', $validated['request_id'])->get();
            foreach ($inventories as $it) {
                // change request_status to Rencana Pembelian
                $it->request_status = $requestStatusPlanning->id;
                $it->request_id = null;
                $it->save();
            }

            $total_price = 0;

            foreach ($validated['inventories'] as $it) {
                $inventory = \App\Models\Inventory::where('id', $it)->first();
                // set request status to 'Pengajuan Pembelian'
                $inventory->request_status = $requestStatusProposal->id;
                $inventory->request_id = $validated['request_id'];
                $inventory->save();

                $total_price += $inventory->price * $inventory->quantity;
            }

            $request->total_price = $total_price;
            $request->save();
        }

        return redirect()->route('request')->withSuccess('Request updated successfully.');
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

    // export pdf
    public function print($id) {
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

        $fileName = 'request-' . $request->roomName . '-' . $request->statusName . '.pdf';

        $pdf = \PDF::loadView('request.pdf', compact('widget'));
        return $pdf->download($fileName);
    }
}
