<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\InventoryIssueRequest;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;


class IssueController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $issues = \App\Models\InventoryIssue::all();
        $status = \App\Models\Status::where('type', 'issue')->get();

        foreach ($issues as $issue) {
            $author = \App\Models\User::where('id', $issue->author_id)->first();
            $issue->author_id = $author->name;
            $room = \App\Models\Room::where('id', $issue->room_id)->first();
            $issue->room_id = $room->name;

            $issue->statusName = $status->where('id', $issue->status)->first()->name;
            $issue->statusColor = $status->where('id', $issue->status)->first()->color;
        }

        $widget = [
            'issues' => $issues,
        ];
        return view('issue', compact('widget'));
    }

    public function create()
    {
        $inventories  = \App\Models\Inventory::all();
        $inventories = $inventories->where('status', '<', '4');
        $rooms = \App\Models\Room::all();
        foreach ($inventories as $inventory) {
            $inventory->room_id = $rooms->where('id', $inventory->room_id)->first()->name;
        }
        $widget = [
            'inventories' => $inventories,
            'rooms' => $rooms,
        ];
        return view('issue.add', compact('widget'));
    }

    public function store(InventoryIssueRequest $request) {
        $validated = $request->validated();
        $inventories = $validated['inventories'];
        $status  = \App\Models\Status::where('type', 'issue')->get();
        
        

        $issue = \App\Models\InventoryIssue::create(
            [
                'author_id' => $validated['author_id'],
                'room_id' => $validated['room_id'],
                'description' => $validated['description'],
                'issued_at' => Carbon :: now('Asia/Jakarta') ,
                'status' => $status->where('name', 'Pengajuan Perbaikan')->first()->id,
            ]
        );
        foreach ($inventories as $inventory) {
            // PROSES GANTI STATUS INVENTORY
            $inventory = \App\Models\Inventory::where('id', $inventory)->first();
            $inventory->issue_id = $issue->id;
            $inventory->issue_status = $status->where('name', 'Pengajuan Perbaikan')->first()->id;
            $inventory->last_author_id = Auth :: user()->id;
            $inventory->save();
        }
        
        // redirect to index 
        return redirect()->route('issue')->withSuccess('Issue added successfully.');
    }

    public function detail($id)
    {
        $issue = \App\Models\InventoryIssue::where('id', $id)->first();
        $rooms = \App\Models\Room::all();
        $status = \App\Models\Status::all();
        $issue->room_name = $rooms->where('id', $issue->room_id)->first()->name;
        // author
        $issue->author = \App\Models\User::where('id', $issue->author_id)->first()->name;

        $inventories = \App\Models\Inventory::where('issue_id', $id)->get();

        foreach ($inventories as $inventory) {
            $inventory->room_name = $rooms->where('id', $inventory->room_id)->first()->name;
            $inventory->condition = $status->where('id', $inventory->status)->first()->name;
        }

        $status = \App\Models\Status::where('type', 'issue')->get();
        $issue->statusName = $status->where('id', $issue->status)->first()->name;
        $issue->statusColor = $status->where('id', $issue->status)->first()->color;
        
        $widget = [
            'issue' => $issue,
            'inventories' => $inventories,
            'rooms' => $rooms,
            'status' => $status,
        ];
        return view('issue.detail', compact('widget'));
    }

    public function edit($id) {
        $issue = \App\Models\InventoryIssue::where('id', $id)->first();
        $rooms = \App\Models\Room::all();
        $status = \App\Models\Status::all();
        $issue->room_name = $rooms->where('id', $issue->room_id)->first()->name;
        // author
        $issue->author = \App\Models\User::where('id', $issue->author_id)->first()->name;

        $allInventories = \App\Models\Inventory::where('issue_id', null)->get();
        $inventories = \App\Models\Inventory::where('issue_id', $id)->get();

        // add inventory to allInventories
        $allInventories = $allInventories->merge($inventories);

        foreach ($inventories as $inventory) {
            $inventory->room_name = $rooms->where('id', $inventory->room_id)->first()->name;
            $inventory->condition = $status->where('id', $inventory->status)->first()->name;
        }

        $status = \App\Models\Status::where('type', 'issue')->get();
        $issue->statusName = $status->where('id', $issue->status)->first()->name;
        $issue->statusColor = $status->where('id', $issue->status)->first()->color;

        
        $widget = [
            'issue' => $issue,
            'inventories' => $inventories,
            'rooms' => $rooms,
            'status' => $status,
            'allInventories' => $allInventories,
        ];
        return view('issue.edit', compact('widget'));
    }

    public function update(Request $request) {
        $validated = $request->validate([
            'issue_id' => 'required',
            'room_id' => 'required',
            'inventories' => 'array|required',
            'description' => 'required',
        ]);

        if ($validated) {
            $status = \App\Models\Status::where('type', 'issue')->get();
            $initialinven = \App\Models\Inventory::where('issue_id', $validated['issue_id'])->get();
            foreach ($initialinven as $initial) {
                $initial->issue_id = null;
                $initial->issue_status = null;
                $initial->save();
            }

            $inventories = $validated['inventories'];
            foreach ($inventories as $inventory) {
                $inventory = \App\Models\Inventory::where('id', $inventory)->first();
                $inventory->issue_id =  $validated['issue_id'];
                $inventory->issue_status = 5;
                $inventory->save();
            }

            $issue = \App\Models\InventoryIssue::where('id', $validated['issue_id'])->first();
            $issue->room_id = $validated['room_id'];
            $issue->description = $validated['description'];
            $issue->save();
        }
        return redirect()->route('issue')->withSuccess('Issue updated successfully.');
    }

    public function approve(Request $request) {
        $validated = $request->validate([
            'issue_id' => 'required',
        ]);

        if ($validated) {
            $issue = \App\Models\InventoryIssue::where('id', $validated['issue_id'])->first();
            $issue->status = 7; // status disetujui
            $issue->save();

            $inventories = \App\Models\Inventory::where('issue_id', $validated['issue_id'])->get();
            foreach ($inventories as $inventory) {  
                $inventory->status = 5; // status baik
                $inventory->save();
            }
        }
        return redirect()->route('issue')->withSuccess('Issue approved successfully.');
    }
}
