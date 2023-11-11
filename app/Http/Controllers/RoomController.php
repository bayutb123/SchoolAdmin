<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Room;
use App\Http\Requests\AddRoomRequest;

class RoomController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $rooms = \App\Models\Room::all()->reverse();

        // get total inventory of each room
        foreach ($rooms as $room) {
            $room->total = \App\Models\Inventory::where('room_id', $room->id)
            ->where('status', '<', 10)
            ->count();
        }

        foreach ($rooms as $room) {
            if ($room->floor == 1) {
                $room->floor = 'Lantai Dasar';
            } else if ($room->floor == 2) {
                $room->floor = 'Lantai 2';
            } else if ($room->floor == 3) {
                $room->floor = 'Lantai 3';
            } else if ($room->floor == 4) {
                $room->floor = 'Lantai 4';
            } else {
                $room->floor = 'Ruang Terbuka Sekolah';
            }
            if ($room->status == "A") {
                $room->status = '<span class="badge badge-success p-2">Baik</span>';
            } else if ($room->status == "B") {
                $room->status = '<span class="badge badge-warning p-2">Kurang</span>';
            } else if ($room->status == "C") {
                $room->status = '<span class="badge badge-danger p-2">Tidak Layak</span>';
            } else {
                $room->status = '<span class="badge badge-secondary p-2">Tidak diketahui</span>';
            }
        }

        $widget = [
            'rooms' => $rooms,
        ];
        
        return view('room', compact('widget'));
    }

    public function create()
    {
        return view('room.add');
    }

    public function store(AddRoomRequest $request) {
        $validated = $request->validated();

        $room = Room::where('name', $request->name)->first();

        if ($room) {
            return redirect()->route('room.create')->withError('Room already exists.');
        }

        if ($validated) {
            $room = Room::create(
                [
                    'type' => $request->type,
                    'name' => $request->name,
                    'floor' => $request->floor,
                    'size' => $request->size,
                    'size_unit' => $request->size_unit,
                    'last_author_id' => $request->last_author_id,
                ]
            );
        }
        return redirect()->route('room.create')->withSuccess('Room added successfully.');
    }

    public function edit($id)
    {
        $room = Room::where('id', $id)->first();
        $widget = [
            'room' => $room,
        ];
        return view('room.edit', compact('widget'));
    }

    public function update(AddRoomRequest $request)
    {
        $validated = $request->validated();
        $room_id = $request->room_id;

        $room = Room::where('id', $room_id)->first();

        if ($validated) {
            $room->type = $request->type;
            $room->name = $request->name;
            $room->floor = $request->floor;
            $room->size = $request->size;
            $room->size_unit = $request->size_unit;
            $room->last_author_id = $request->last_author_id;
            $room->save();
        }
        return redirect()->route('room')->withSuccess('Room updated successfully.');
    }
}
