<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\InventoryIssueRequest;


class IssueController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $issues = \App\Models\InventoryIssue::all();

        $widget = [
            'issues' => $issues,
        ];
        return view('issue', compact('widget'));
    }

    public function create()
    {
        $inventories  = \App\Models\Inventory::all();
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
        
        foreach ($inventories as $inventory) {
            // PROSES INPUT INVENTORY ISSUE GROUP
        }
        dd($validated);
    }
}
