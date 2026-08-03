<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Place;
use App\Models\Legend;
use Illuminate\Database\Eloquent\Builder;
use App\Services\SortService;

class PlaceController extends Controller
{
    protected $sortService;

    public function __construct(SortService $sortService)
    {
        $this->sortService = $sortService;
    }

    /**
     * Filter and display all places, download a CSV file of the results (if the format is specified).
     */
    public function index(Request $request)
    {
        $places = Place::all()->toQuery();
        if ($request->global_search != '') {
            $places = $this->sortService->global($places, 'places', $request->global_search);
        }

        if ($request->name != '') {
            $places = $places->where('name', 'LIKE', '%'.$request->name.'%');
        }
        if ($request->sort != '') {
            $places = $places->withCount('legends')->orderBy('legends_count', $request->sort);
        } else {
            $places = $places->orderBy('name');
        }

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

        $places = $places->paginate(app('items_per_page'));
        if ($request->global_search != '') {
            $places = $this->sortService->highlight($places, 'places', $request->global_search, $request);
        }
        return view('places.index', compact('places'));
    }

    /**
     * Show the form for creating a new place.
     */
    public function create()
    {
        $place = new Place();
        $exists = False;
        return view('places.edit', compact('place', 'exists'));
    }

    /**
     * Store a newly created place in storage.
     */
    public function store(Request $request)
    {
        $place = new Place();

        $this->save($request, $place);
        return redirect()->route('places.show', $place->id);
    }

    /**
     * Display the specified place.
     */
    public function show(string $id, Request $request)
    {
        $place = Place::find($id);
        if (!$place) {
            return redirect()->route('places.index')->with('not-found', __('resources.none_single'));
        }
        $item = $place;

        $request->origin = "place";
        $request->item_id = $id;
        $sorted = $this->sortService->sort($request);
        $paginator = $sorted['legends']->paginate(app('items_per_page'));
        $sort = $sorted['sort'];

        // Calculates the index page the specified place is on (needed for a return link to the index).
        $place_ids = Place::all()->toQuery()->orderBy('name')->pluck('id')->toArray();
        $i = array_search($place->id, $place_ids);
        $page = intval($i / 20) + 1;

        $paginator = $this->sortService->text($paginator, isset($request->text) ? $request->text : '');
        return view('places.show', compact('place', 'page', 'paginator', 'sort', 'item'));
    }

    /**
     * Show the form for editing the specified place.
     */
    public function edit(string $id)
    {
        $place = Place::find($id);
        if (!$place) {
            return redirect()->route('places.index')->with('not-found', __('resources.none_single'));
        }
        $exists = True;
        return view('places.edit', compact('place', 'exists'));
    }

    /**
     * Update the specified place in storage.
     */
    public function update(Request $request, string $id)
    {
        $place = Place::find($id);
        if (!$place) {
            return redirect()->route('places.index')->with('not-found', __('resources.none_single'));
        }

        $this->save($request, $place);
        return redirect()->route('places.show', $place->id);
    }

    /**
     * Remove the specified place from storage.
     */
    public function destroy(string $id)
    {
        if ($id == 1) { return back(); }
        $place = Place::find($id);
        if (!$place) {
            return redirect()->route('places.index')->with('not-found', __('resources.none_single'));
        }
        $place->delete();
        return redirect()->route('places.index');
    }

    /**
     * Filter and display places with known locations (and their legends) in map view.
     */
    public function map(Request $request) {
        $php_coordinates = [];
        $chapters_titles = [];
        $titles_selected = [];

        $filtered = $this->filter($request);
        $titles_selected = $filtered['titles'];
        $places = $filtered['places'];

        foreach ($places as $place) {
            $php_coordinates[$place->id] = [$place->latitude, $place->longitude, $place->legends->count()];
        }

        // All subchapters are associated with their chapters in order to create an organized list for selection.
        $titles_query = Legend::select('chapter_lv', 'chapter_de', 'title_lv', 'title_de')->distinct('title_lv')->get();
        foreach($titles_query as $title) {
            if (isset($chapters_titles[$title['chapter_lv'].' / '.$title['chapter_de']])) {
                array_push($chapters_titles[$title['chapter_lv'].' / '.$title['chapter_de']], [$title['title_lv'], $title['title_de']]);
            } else {
                $chapters_titles[$title['chapter_lv'].' / '.$title['chapter_de']] = [[$title['title_lv'], $title['title_de']]];
            }
            
        }
        
        $coordinates = json_encode($php_coordinates);
        if ($request->exclude_unknown == "on") { $exclude_unknown = 1; }
        else { $exclude_unknown = 0; }
        return view('home', compact('places', 'coordinates', 'chapters_titles', 'titles_selected', 'exclude_unknown'));
    }

    public function lists(Request $request) {
        $filtered = $this->filter($request);
        $places = $filtered['places'];

        return view('partials.lists', compact('places'));
    }

    /**
     * Save a place's data in storage.
     */
    public function save(Request $request, Place $place)
    {
        $request->validate([
            'name' => 'required|max:32',
            'latitude' => 'numeric|between:-90,90|decimal:0,6|nullable',
            'longitude' => 'numeric|between:-180,180|decimal:0,6|nullable',
        ]);

        $place->name = $request->name;
        $place->latitude = $request->latitude;
        $place->longitude = $request->longitude;
        $place->save();
    }

    public function filter(Request $request) {
        $filtered = [];

        $titles_selected = [];
        if (isset($request->titles)) {
            foreach($request->titles as $title) {           // The selected titles are required within the view, which is why
                array_push($titles_selected, $title);       // a new variable that can be passed to said view is created.
            }
            $places = Place::whereHas('legends', function(Builder $query) use ($titles_selected) {
                $query->whereIn('title_lv', $titles_selected);
            })->with(['legends' => function($query) use ($titles_selected) {
                    $query->whereIn('title_lv', $titles_selected);
                }
            ])->get();
        } else {
            $places = Place::with('legends')->get();
        }

        $filtered['titles'] = $titles_selected;
        $filtered['places'] = $places;
        return $filtered;
    }
}
