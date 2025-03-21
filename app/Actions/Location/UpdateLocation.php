<?php

namespace App\Actions\Location;

use App\Models\Location;
use Illuminate\Support\Facades\Auth;

class UpdateLocation
{

    public function update(Location $location, Array $datas): bool
    {   
        return $location->update($datas);
    }
}
