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
        $inventories = $inventories->where('status', '!=', 'Dalam Perbaikan');
        $rooms = \App\Models\Room::all();
        foreach ($inventories as $inventory) {
            $inventory->room_id = $rooms->where('id', $inventory->room_id)->first()->name;
        }
        $widget = [
            'inventories' => $inventories,
            'rooms' => $rooms,
        ];
        return view('add.invenissue', compact('widget'));
    }

    public function store(InventoryIssueRequest $request) {
        $validated = $request->validated();
        $inventories = $validated['inventories'];
        $status  = \App\Models\Status::where('type', 'issue')->get();
        
        foreach ($inventories as $inventory) {
            // PROSES GANTI STATUS INVENTORY
            $inventory = \App\Models\Inventory::where('id', $inventory)->first();
            $inventory->status = $status->where('name', 'Dalam Perbaikan')->first()->id;
            $inventory->last_author_id = Auth :: user()->id;
            $inventory->save();
        }

        $issue = \App\Models\InventoryIssue::create(
            [
                'author_id' => $validated['author_id'],
                'room_id' => $validated['room_id'],
                'description' => $validated['description'],
                'issued_at' => Carbon :: now('Asia/Jakarta') ,
                'status' => $status->where('name', 'Pending')->first()->id,
            ]
        );
        
        // redirect to index 
        return redirect()->route('issue')->withSuccess('Issue added successfully.');
    }
}
