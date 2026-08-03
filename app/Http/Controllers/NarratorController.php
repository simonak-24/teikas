<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Narrator;
use App\Services\SortService;

class NarratorController extends Controller
{
    protected $sortService;

    public function __construct(SortService $sortService)
    {
        $this->sortService = $sortService;
    }

    /**
     * Filter and display all narrators, download a CSV file of the results (if the format is specified).
     */
    public function index(Request $request)
    {
        $narrators = Narrator::all()->toQuery();
        if ($request->global_search != '') {
            $narrators = $this->sortService->global($narrators, 'narrators', $request->global_search);
        }

        if ($request->fullname != '') {
            $narrators = $narrators->where('fullname', 'LIKE', '%'.$request->fullname.'%');
        }
        if ($request->gender != '') {
            if ($request->gender == '?') {
                $narrators = $narrators->whereNull('gender');
            } else {
                $narrators = $narrators->where('gender', $request->gender);
            }
        }
        if ($request->sort != '') {
            $narrators = $narrators->withCount('legends')->orderBy('legends_count', $request->sort);
        } else {
            $narrators = $narrators->orderBy('fullname');
        }

        if (isset($request->format)) {
            $filename = 'narrators_'.strval(rand()).'.csv';         // To prevent errors when two users attempt to download a file at the same time,
                                                                    // the files are given randomized names, preventing a colision.
            $columns = [__('resources.person_fullname'), __('resources.person_gender'), __('resources.narrator_count')];
            $query = [$request->fullname, $request->gender, $request->sort];
            
            $file = fopen($filename, "w");
            fputcsv($file, $columns);
            fputcsv($file, $query);
            $narrators_csv = $narrators->get();
            foreach($narrators_csv as $narrator) {
                if (isset($narrator->gender)) {
                    $gender = $narrator->gender; 
                } else {
                    $gender = 'null';
                }
                fputcsv($file, [$narrator->fullname, $gender, count($narrator->legends)]);
            }
            fclose($file);

            $headers = [
                'Content-Type' => 'text/csv',
            ];
            return response()->download($filename, 'narrators_'.now()->format('Y-m-d_H-i-s').'.csv', $headers)->deleteFileAfterSend(true);
        }

        $narrators = $narrators->paginate(app('items_per_page'));
        if ($request->global_search != '') {
            $narrators = $this->sortService->highlight($narrators, 'narrators', $request->global_search, $request);
        }
        return view('narrators.index', compact('narrators'));
    }

    /**
     * Show the form for creating a new narrator.
     */
    public function create()
    {
        $narrator = new Narrator();
        if ($narrator->gender == null) { $narrator->gender = '?'; }
        $exists = False;
        return view('narrators.edit', compact('narrator', 'exists'));
    }

    /**
     * Store a newly created narrator in storage.
     */
    public function store(Request $request)
    {
        $narrator = new Narrator();
        
        $this->save($request, $narrator);
        return redirect()->route('narrators.show', $narrator->id);
    }

    /**
     * Display the specified narrator.
     */
    public function show(string $id, Request $request)
    {
        $narrator = Narrator::find($id);
        if (!$narrator) {
            return redirect()->route('narrators.index')->with('not-found', __('resources.none_single'));
        }
        $item = $narrator;

        $request->origin = "narrator";
        $request->item_id = $id;
        $sorted = $this->sortService->sort($request);
        $paginator = $sorted['legends']->paginate(app('items_per_page'));
        $sort = $sorted['sort'];

        // Calculates the index page the specified narrator is on (needed for a return link to the index).
        $narrator_ids = Narrator::all()->toQuery()->orderBy('fullname')->pluck('id')->toArray();
        $i = array_search($narrator->id, $narrator_ids);
        $page = intval($i / 20) + 1;

        $paginator = $this->sortService->text($paginator, isset($request->text) ? $request->text : '');
        return view('narrators.show', compact('narrator', 'page', 'paginator', 'sort', 'item'));
    }

    /**
     * Show the form for editing the specified narrator.
     */
    public function edit(string $id)
    {
        $narrator = Narrator::find($id);
        if (!$narrator) {
            return redirect()->route('narrators.index')->with('not-found', __('resources.none_single'));
        }
        if ($narrator->gender == null) { $narrator->gender = '?'; }
        $exists = True;
        return view('narrators.edit', compact('narrator', 'exists'));
    }

    /**
     * Update the specified narrator in storage.
     */
    public function update(Request $request, string $id)
    {
        $narrator = Narrator::find($id);
        if (!$narrator) {
            return redirect()->route('narrators.index')->with('not-found', __('resources.none_single'));
        }

        $this->save($request, $narrator);
        return redirect()->route('narrators.show', $narrator->id);
    }

    /**
     * Remove the specified narrator from storage.
     */
    public function destroy(string $id)
    {
        if ($id == 1) { return back(); }
        $narrator = Narrator::find($id);
        if (!$narrator) {
            return redirect()->route('narrators.index')->with('not-found', __('resources.none_single'));
        }
        $narrator->delete();
        return redirect()->route('narrators.index');
    }

    /**
     * Save a narrator's data in storage.
     */
    public function save(Request $request, Narrator $narrator)
    {
        $request->validate([
            'fullname' => 'required|max:64',
            'gender' => 'in:M,F,?',
            'external_id' => 'max:7|regex:/^[0-9]+$/|nullable',
        ]);

        $narrator->fullname = $request->fullname;
        $narrator->gender = $request->gender;
        if ($narrator->gender == '?') { $narrator->gender = null; }
        $narrator->external_identifier = $request->external_id;
        $narrator->save();
    }
}
