<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Place;
use App\Models\Legend;
use Illuminate\Database\Eloquent\Builder;

class PlaceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $places = Place::all()->toQuery();
        if ($request->name != '') {
            $places = $places->where('name', 'LIKE', '%'.$request->name.'%');
        }
        $places = $places->orderBy('name');

        if (isset($request->format)) {
            $filename = 'places_'.strval(rand()).'.csv';        // To prevent errors when two users attempt to download a file at the same time,
                                                                // the files are given randomized names, preventing a colision.
            $columns = [__('resources.place_name'), __('resources.place_latitude'), __('resources.place_longitude')];
            $query = [$request->name];
            
            $file = fopen($filename, "w");
            fputcsv($file, $columns);
            fputcsv($file, $query);
            $places_csv = $places->get();
            foreach($places_csv as $place) {
                if ($place->latitude != 0) {
                    $latitude = $place->latitude; 
                } else {
                    $latitude = 'null';
                }
                if ($place->longitude != 0) {
                    $longitude = $place->longitude; 
                } else {
                    $longitude = 'null';
                }
                fputcsv($file, [$place->name, $latitude, $longitude]);
            }
            fclose($file);

            $headers = [
                'Content-Type' => 'text/csv',
            ];
            return response()->download($filename, 'places_'.now()->format('Y-m-d_H-i-s').'.csv', $headers)->deleteFileAfterSend(true);
        }

        $places = $places->paginate(20);
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
            'name' => 'required|max:32',
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
        $place = Place::find($id);
        if (!$place) {
            return redirect()->back()->with('not-found', __('resources.none_single'));
        }

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
        $place = Place::find($id);
        if (!$place) {
            return redirect()->back()->with('not-found', __('resources.none_single'));
        }
        return view('places.edit', compact('place'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $place = Place::find($id);
        if (!$place) {
            return redirect()->back()->with('not-found', __('resources.none_single'));
        }

        $request->validate([
            'name' => 'required|max:32',
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
        $place = Place::find($id);
        if (!$place) {
            return redirect()->route('places.index')->with('not-found', __('resources.none_single'));
        }
        $place->delete();
        return redirect()->route('places.index');
    }

    /**
     * Show reasource in map view.
     */
    public function map(Request $request) {
        // $places = Place::all();
        $php_coordinates = array();
        $chapters_titles = [];
        $titles_selected = [];

        if (isset($request->titles)) {
            foreach($request->titles as $title){
                array_push($titles_selected, $title);
            }
            $places = Place::whereHas('legends', function(Builder $query) use ($titles_selected) {
                $query->whereIn('title_lv', $titles_selected);
            })->with(['legends' => function($query) use ($titles_selected) {
                    $query->whereIn('title_lv', $titles_selected);
                }
            ])->get();
        } else {
            $places = Place::all();
        }

        foreach ($places as $place) {
            if ($place->latitude != 0 && $place->latitude != 0) {
                $php_coordinates[$place->id] = [$place->latitude, $place->longitude];
            }
        }

        $titles_query = Legend::select('chapter_lv', 'chapter_de', 'title_lv', 'title_de')->distinct('title_lv')->get();
        foreach($titles_query as $title) {
            if (isset($chapters_titles[$title['chapter_lv'].' / '.$title['chapter_de']])) {
                array_push($chapters_titles[$title['chapter_lv'].' / '.$title['chapter_de']], [$title['title_lv'], $title['title_de']]);
            } else {
                $chapters_titles[$title['chapter_lv'].' / '.$title['chapter_de']] = [[$title['title_lv'], $title['title_de']]];
            }
            
        }
        
        $coordinates = json_encode($php_coordinates);
        return view('home', compact('places', 'coordinates', 'chapters_titles', 'titles_selected'));
    }
}
