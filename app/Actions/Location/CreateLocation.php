<?php

namespace App\Actions\Location;

use App\Events\LocationCreated;
use App\Models\Location;
use Illuminate\Support\Facades\Auth;

class CreateLocation
{

    public function create(Array $location): Location
    {   
        $location['team_id'] = Auth::user()->current_team_id;
        $location = Location::create($location);
        LocationCreated::dispatch($location);
        return $location;
    }
}
