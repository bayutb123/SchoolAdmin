<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\InventoryIssueRequest;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use PDF;


class IssueController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        // if user is admin, show all issues
        if (Auth::user()->role_id == 1) {
            $issues = \App\Models\InventoryIssue::all()->reverse();
        } else {
            $issues = \App\Models\InventoryIssue::where('author_id', Auth::user()->id)->get()->reverse();
        }
        $status = \App\Models\Status::all();
        $issuedStatus = \App\Models\Status::where('type', 'issue')->get();

        foreach ($issues as $issue) {
            $author = \App\Models\User::where('id', $issue->author_id)->first();
            $issue->author_id = $author->name;
            $room = \App\Models\Room::where('id', $issue->room_id)->first();
            $issue->room_id = $room->name;

            // get status id where it name  == 'Pengajuan Perbaikan'

            // add new if created today
            if ($issue->created_at->isToday()) {
                $issue->new = true;
            }

            // if status is in issuedStatus, add isIssued
            if ($issuedStatus->contains('id', $issue->status)) {
                $issue->isProcessed = true;
            }

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
        // get status id where name == 'Pengajuan Perbaikan'
        $issuedStatus = \App\Models\Status::where('name', 'Pengajuan Perbaikan')->first();
        // get status id where name == 'Pengadaan'
        $requestStatus = \App\Models\Status::where('name', 'Pengadaan')->first();
        // get status id where name == 'Baik'
        $goodStatus = \App\Models\Status::where('name', 'Baik')->first();
        // get all inventories where issue_id is null and request_id is null
        $inventories = \App\Models\Inventory::where('issue_id', null)
            ->where('status', '<', $requestStatus->id)
            ->where('status', '!=', $goodStatus->id)
            ->get()->reverse();
        $status = \App\Models\Status::all();
        $rooms = \App\Models\Room::all();
        foreach ($inventories as $inventory) {
            $inventory->room_id = $rooms->where('id', $inventory->room_id)->first()->name;
            $inventory->statusName = $status->where('id', $inventory->status)->first()->name;
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
        $status  = \App\Models\Status::where('type', 'inventory')->get();

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
        $approvedStatus = \App\Models\Status::where('name', 'Disetujui')->first();

        if ($issue->status == $approvedStatus->id) {
            $issue->isApproved = true;
        } else {
            $issue->isApproved = false;
        }

        $issue->room_name = $rooms->where('id', $issue->room_id)->first()->name;
        // author
        $issue->author = \App\Models\User::where('id', $issue->author_id)->first()->name;

        $inventories = \App\Models\Inventory::where('issue_id', $id)->get();

        foreach ($inventories as $inventory) {
            $inventory->room_name = $rooms->where('id', $inventory->room_id)->first()->name;
            $inventory->condition = $status->where('id', $inventory->status)->first()->name;
        }

        $status = \App\Models\Status::all();
        $issue->statusColor = $status->where('id', $issue->status)->first()->color;
        $issue->statusName = $status->where('id', $issue->status)->first()->name;
        
        $widget = [
            'issue' => $issue,
            'inventories' => $inventories,
            'rooms' => $rooms,
            'status' => $status,
        ];
        return view('issue.detail', compact('widget'));
    }

    // delete issue
    public function destroy(Request $request) {
        $validated = $request->validate([
            'issue_id' => 'required',
        ]);

        if ($validated) {
            $inventories = \App\Models\Inventory::where('issue_id', $validated['issue_id'])->get();
            foreach ($inventories as $inventory) {
                $inventory->issue_id = null;
                $inventory->issue_status = null;
                $inventory->last_author_id = Auth::user()->id;
                $inventory->save();
            }

            $issue = \App\Models\InventoryIssue::where('id', $validated['issue_id'])->first();
            $issue->delete();
        }
        return redirect()->route('issue')->withSuccess('Issue deleted successfully.');
    }

    public function edit($id) {
        $issue = \App\Models\InventoryIssue::where('id', $id)->first();
        $rooms = \App\Models\Room::all();
        $status = \App\Models\Status::all();
        $issue->room_name = $rooms->where('id', $issue->room_id)->first()->name;
        // author
        $issue->author = \App\Models\User::where('id', $issue->author_id)->first()->name;

        $allInventories = \App\Models\Inventory::where('issue_id', null)->where('status', '<', 10)->get();
        $inventories = \App\Models\Inventory::where('issue_id', $id)->get();

        // add inventory to allInventories
        $allInventories = $allInventories->merge($inventories);

        foreach ($inventories as $inventory) {
            $inventory->room_name = $rooms->where('id', $inventory->room_id)->first()->name;
            $inventory->condition = $status->where('id', $inventory->status)->first()->name;
        }

        foreach ($allInventories as $it) {
            $it->roomName = $rooms->where('id', $it->room_id)->first()->name;
            $it->statusName = $status->where('id', $it->status)->first()->name;
            $it->statusColor = $status->where('id', $it->status)->first()->color;
        }

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

    public function printAll() {
        $users = \App\Models\User::all();
        $issues = \App\Models\InventoryIssue::all();
        $rooms = \App\Models\Room::all();
        $status = \App\Models\Status::all();
        $issuedStatus = \App\Models\Status::where('type', 'issue')->get();

        foreach ($issues as $issue) {
            $author = $users->where('id', $issue->author_id)->first();
            $room = $rooms->where('id', $issue->room_id)->first();
            $issue->authorName = $author->name;
            $issue->roomName = $room->name;
            $issue->statusName = $status->where('id', $issue->status)->first()->name;
            $issue->statusColor = $status->where('id', $issue->status)->first()->color;
        }

        $widget = [
            'issues' => $issues,
            'date' => Carbon::now('Asia/Jakarta')->format('d F Y'),
        ];

        $fileName = 'issue-all.pdf'. Carbon::now('Asia/Jakarta')->format('d-m-Y');

        $pdf = PDF::loadView('issue.pdf-all', compact('widget'));
        // landscape
        $pdf->setPaper('a4', 'landscape');
        return $pdf->download(str_replace(' ', '', $fileName));
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

        // get status id where name == 'Disetujui'
        $approvedStatus = \App\Models\Status::where('type', 'issue')->where('name', 'Disetujui')->first();

        if ($validated) {
            $issue = \App\Models\InventoryIssue::where('id', $validated['issue_id'])->first();
            $issue->status = $approvedStatus->id; // status disetujui
            $issue->save();

            $inventories = \App\Models\Inventory::where('issue_id', $validated['issue_id'])->get();
            foreach ($inventories as $inventory) {
                $inventory->issue_status = $approvedStatus->id;
                $inventory->save();
            }
        }
        return redirect()->route('issue')->withSuccess('Issue approved successfully.');
    }

    public function print($id) {
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

        $fileName = 'issue-' . $issue->room_name . '-' . $issue->statusName . '.pdf';

        $pdf = PDF::loadView('issue.pdf', compact('widget'));
        return $pdf->download(str_replace(' ', '', $fileName));
    } 
}
