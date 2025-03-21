<?php

namespace App\Http\Controllers;

use App\Http\Resources\LocationResource;
use App\Http\Resources\LocationResourceCollection;
use App\Models\Location;
use App\Models\Team;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $locations = Location::all();
        return Inertia::render('Dashboard',[
            'locations' => LocationResource::collection($locations),
            'userTeamId' => $user->currentTeam->id,
            'userTeamIds' => $user->teams->pluck('id'),
        ]);
    }
}
