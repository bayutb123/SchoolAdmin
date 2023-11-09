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
        $rooms = \App\Models\Room::all();

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
                $room->status = '<span class="badge badge-success">Baik</span>';
            } else if ($room->status == "B") {
                $room->status = '<span class="badge badge-warning">Kurang</span>';
            } else if ($room->status == "C") {
                $room->status = '<span class="badge badge-danger">Tidak Layak</span>';
            } else {
                $room->status = '<span class="badge badge-secondary">Tidak diketahui</span>';
            }
        }

        $widget = [
            'rooms' => $rooms,
        ];
        
        return view('room', compact('widget'));
    }

    public function create()
    {
        return view('add.room');
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
}
