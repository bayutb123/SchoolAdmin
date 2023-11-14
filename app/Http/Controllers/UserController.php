<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Role;

class UserController extends Controller
{
    // constructor
    public function __construct()
    {
        $this->middleware('auth');
    }

    // index
    public function index()
    {
        $users = User::all();
        $role = Role::all();

        // set role name foreach user
        foreach ($users as $user) {
            $user->roleName = $role->where('id', $user->role_id)->first()->description;
        }

        // exclude current user
        $users = $users->filter(function ($user) {
            return $user->id != auth()->user()->id;
        });

        $userCount = $users->count();
        
        $widget = [
            'users' => $users,
            'userCount' => $userCount,
        ];

        return view('users' , compact('widget'));
    }
    
    // edit
    public function edit($id)
    {
        $user = User::findOrFail($id);
        $role = Role::all();

        $user->fullName = $user->name . ' ' . $user->last_name;

        $widget = [
            'user' => $user,
            'role' => $role,
        ];
        
        return view('user.edit' , compact('widget'));
    }

    // update
    public function update(Request $request) 
    {
        $request->validate([
            'user_id' => 'required|integer',
            'role_id' => 'required|integer',
        ]);

        // get current user role
        $currentUserRole = User::findOrFail(auth()->user()->id)->role_id;

        if ($currentUserRole == 1) {
            $user = User::findOrFail($request->input('user_id'));
            $user->role_id = $request->input('role_id');
            $user->save();
        } else {
            return redirect()->route('users')->withError('You are not allowed to update user role.');
        }
        
        return redirect()->route('users')->withSuccess('User updated successfully.');  
    }
}
