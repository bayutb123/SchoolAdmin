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
        
        return view('room');
    }

    public function create()
    {
        return view('add.room');
    }

    public function store(AddRoomRequest $request) {
        $validated = $request->validated();

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
