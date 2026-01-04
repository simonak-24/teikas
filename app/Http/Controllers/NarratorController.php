<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Narrator;

class NarratorController extends Controller
{
    /**
     * Filter and display all narrators, download a CSV file of the results (if the format is specified).
     */
    public function index(Request $request)
    {
        $narrators = Narrator::all()->toQuery();
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

        $paginator = $narrators->paginate(20);
        return view('narrators.index', compact('paginator'));
    }

    /**
     * Show the form for creating a new narrator.
     */
    public function create()
    {
        $narrator = new Narrator();
        if ($narrator->gender == null) { $narrator->gender = '?'; }
        return view('narrators.create', compact('narrator'));
    }

    /**
     * Store a newly created narrator in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'fullname' => 'required|max:64',
            'gender' => 'in:M,F,?',
            'external_id' => 'max:7|regex:/^[0-9]+$/|nullable',
        ]);

        $narrator = new Narrator();
        $narrator->fullname = $request->fullname;
        $narrator->gender = $request->gender;
        if ($narrator->gender == '?') { $narrator->gender = null; }
        $narrator->external_identifier = $request->external_id;
        $narrator->save();
        return redirect()->route('narrators.show', $narrator->id);
    }

    /**
     * Display the specified narrator.
     */
    public function show(string $id)
    {
        $narrator = Narrator::find($id);
        if (!$narrator) {
            return redirect()->route('narrators.index')->with('not-found', __('resources.none_single'));
        }

        // Calculates the index page the specified narrator is on (needed for a return link to the index).
        $narrator_ids = Narrator::all()->toQuery()->orderBy('fullname')->pluck('id');
        $i = 0;
        foreach($narrator_ids as $narrator_id) {
            if ($id == $narrator_id) {
                $page = intval($i / 20) + 1;
                break;
            } else {
                $i = $i + 1;
            }
        }

        return view('narrators.show', compact('narrator', 'page'));
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
        return view('narrators.edit', compact('narrator'));
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
        return redirect()->route('narrators.show', $narrator->id);
    }

    /**
     * Remove the specified narrator from storage.
     */
    public function destroy(string $id)
    {
        $narrator = Narrator::find($id);
        if (!$narrator) {
            return redirect()->route('narrators.index')->with('not-found', __('resources.none_single'));
        }
        $narrator->delete();
        return redirect()->route('narrators.index');
    }
}
