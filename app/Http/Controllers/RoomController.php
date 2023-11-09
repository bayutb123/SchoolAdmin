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
                ]
            );
        }
        return redirect()->route('room.create')->withSuccess('Room added successfully.');
    }
}
