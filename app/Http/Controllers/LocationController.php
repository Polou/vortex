<?php

namespace App\Http\Controllers;

use App\Actions\Location\CreateLocation;
use App\Actions\Location\UpdateLocation;
use App\Models\Location;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class LocationController extends Controller
{
    public function index(Request $request)
    {
        $category = $request->input('category');

        $locations = Location::when($category, function($query, $category){
            $query->where('category', $category);
        })->get();

        return Inertia::render('Location',[
            'locations' => $locations,
        ]);
    }

    public function store(Request $request, CreateLocation $createLocation)
    {
        $location = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'category' => 'string',
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
        ]);

        $createLocation->create($location);
        $request->session()->flash('flash.banner', 'Location ajoutée avec succès !');
        
        return redirect()->route('locations.index');
    }

    public function destroy(Location $location)
    {
        $location->delete();
        
        return redirect()->back();
    }
    

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Location $location, UpdateLocation $updateLocation)
    {
        $validatedDatas = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
        ]);

        $updateLocation->update($location, $validatedDatas);
        $request->session()->flash('flash.banner', 'Location modifiée avec succès !');

        return redirect()->back();
    }
}
