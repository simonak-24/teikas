<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Place;

class PlaceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $places = Place::all()->toQuery()->orderBy('name')->paginate(20);
        return view('places.index', compact('places'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $place = new Place();
        return view('places.create', compact('place'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate( [
            'name' => 'required',
            'latitude' => 'numeric|between:-90,90|nullable',
            'longitude' => 'numeric|between:-180,180|nullable',
        ]);

        $place = new Place();
        $place->name = $request->name;
        $place->latitude = $request->latitude;
        $place->longitude = $request->longitude;
        $place->save();
        return redirect()->route('places.show', $place->id);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $place = Place::findOrfail($id);

        $place_ids = Place::all()->toQuery()->orderBy('name')->pluck('id');
        $i = 0;
        foreach($place_ids as $place_id) {
            if ($id == $place_id) {
                $page = intval($i / 20) + 1;
                break;
            } else {
                $i = $i + 1;
            }
        }

        return view('places.show', compact('place', 'page'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $place = Place::findOrfail($id);
        return view('places.edit', compact('place'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $place = Place::findOrfail($id);
        $request->validate([
            'name' => 'required',
            'latitude' => 'numeric|between:-90,90|nullable',
            'longitude' => 'numeric|between:-180,180|nullable',
        ]);

        $place->name = $request->name;
        $place->latitude = $request->latitude;
        $place->longitude = $request->longitude;
        $place->save();
        return redirect()->route('places.show', $place->id);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        Place::findOrfail($id)->delete();
        return redirect()->route('places.index');
    }
}
