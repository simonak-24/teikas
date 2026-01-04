<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Collector;

class CollectorController extends Controller
{
    /**
     * Filter and display all collectors, download a CSV file of the results (if the format is specified).
     */
    public function index(Request $request)
    {
        $collectors = Collector::all()->toQuery();
        if ($request->fullname != '') {
            $collectors = $collectors->where('fullname', 'LIKE', '%'.$request->fullname.'%');
        }
        if ($request->gender != '') {
            if ($request->gender == '?') {
                $collectors = $collectors->whereNull('gender');
            } else {
                $collectors = $collectors->where('gender', $request->gender);
            }
        }
        if ($request->sort != '') {
            $collectors = $collectors->withCount('legends')->orderBy('legends_count', $request->sort);
        } else {
            $collectors = $collectors->orderBy('fullname');
        }
        
        if (isset($request->format)) {
            $filename = 'collectors_'.strval(rand()).'.csv';        // To prevent errors when two users attempt to download a file at the same time,
                                                                    // the files are given randomized names, preventing a colision.
            $columns = [__('resources.person_fullname'), __('resources.person_gender'), __('resources.collector_count')];
            $query = [$request->fullname, $request->gender, $request->sort];
            
            $file = fopen($filename, "w");
            fputcsv($file, $columns);
            fputcsv($file, $query);
            $collectors_csv = $collectors->get();
            foreach($collectors_csv as $collector) {
                if (isset($collector->gender)) {
                    $gender = $collector->gender; 
                } else {
                    $gender = 'null';
                }
                fputcsv($file, [$collector->fullname, $gender, count($collector->legends)]);
            }
            fclose($file);

            $headers = [
                'Content-Type' => 'text/csv',
            ];
            return response()->download($filename, 'collectors_'.now()->format('Y-m-d_H-i-s').'.csv', $headers)->deleteFileAfterSend(true);
        }

        $paginator = $collectors->paginate(20);
        return view('collectors.index', compact('paginator'));
    }

    /**
     * Show the form for creating a new collector.
     */
    public function create()
    {
        $collector = new Collector();
        if ($collector->gender == null) { $collector->gender = '?'; }
        return view('collectors.create', compact('collector'));
    }

    /**
     * Store a newly created collector in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'fullname' => 'required|max:64',
            'gender' => 'in:M,F,?',
            'external_id' => 'max:7|regex:/^[0-9]+$/|nullable',
        ]);

        $collector = new Collector();
        $collector->fullname = $request->fullname;
        $collector->gender = $request->gender;
        if ($collector->gender == '?') { $collector->gender = null; }
        $collector->external_identifier = $request->external_id;
        $collector->save();
        return redirect()->route('collectors.show', $collector->id);
    }

    /**
     * Display the specified collector.
     */
    public function show(string $id)
    {
        $collector = Collector::find($id);
        if (!$collector) {
            return redirect()->route('collectors.index')->with('not-found', __('resources.none_single'));
        }
        
        // Calculates the index page the specified collector is on (needed for a return link to the index).
        $collector_ids = Collector::all()->toQuery()->orderBy('fullname')->pluck('id');
        $i = 0;
        foreach($collector_ids as $collector_id) {
            if ($id == $collector_id) {
                $page = intval($i / 20) + 1;
                break;
            } else {
                $i = $i + 1;
            }
        }

        return view('collectors.show', compact('collector', 'page'));
    }

    /**
     * Show the form for editing the specified collector.
     */
    public function edit(string $id)
    {
        $collector = Collector::find($id);
        if (!$collector) {
            return redirect()->route('collectors.index')->with('not-found', __('resources.none_single'));
        }
        if ($collector->gender == null) { $collector->gender = '?'; }
        return view('collectors.edit', compact('collector'));
    }

    /**
     * Update the specified collector in storage.
     */
    public function update(Request $request, string $id)
    {
        $collector = Collector::find($id);
        if (!$collector) {
            return redirect()->route('collectors.index')->with('not-found', __('resources.none_single'));
        }

        $request->validate([
            'fullname' => 'required|max:64',
            'gender' => 'in:M,F,?',
            'external_id' => 'max:7|regex:/^[0-9]+$/|nullable',
        ]);

        $collector->fullname = $request->fullname;
        $collector->gender = $request->gender;
        if ($collector->gender == '?') { $collector->gender = null; }
        $collector->external_identifier = $request->external_id;
        $collector->save();
        return redirect()->route('collectors.show', $collector->id);
    }

    /**
     * Remove the specified collector from storage.
     */
    public function destroy(string $id)
    {
        $collector = Collector::find($id);
        if (!$collector) {
            return redirect()->route('collectors.index')->with('not-found', __('resources.none_single'));
        }
        $collector->delete();
        return redirect()->route('collectors.index');
    }
}
