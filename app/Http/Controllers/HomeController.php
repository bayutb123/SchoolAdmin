<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\InventoryIssue;
use App\Models\InventoryRequest;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        
        
        $user = Auth()->user();

        // count total issues and requests
        $totalIssues = InventoryIssue::where('author_id', $user->id)->count();
        $totalRequests = InventoryRequest::where('author_id', $user->id)->count();
        $clearedIssues = InventoryIssue::where('author_id', $user->id)->where('status', 'Selesai')->count();
        $clearedRequests = InventoryRequest::where('author_id', $user->id)->where('status', 'Selesai')->count();

        // Test dulu
        // $percentageIssues = ($clearedIssues / $totalIssues) * 100;
        // $percentageRequests = ($clearedRequests / $totalRequests) * 100;
        
        $percentageIssues = (60 / 100) * 100;
        $percentageRequests = (91 / 100) * 100;

        $widget = [
            'percentageIssues' => $percentageIssues,
            'percentageRequests' => $percentageRequests
        ];

        return view('home', compact('widget'));
    }
}
