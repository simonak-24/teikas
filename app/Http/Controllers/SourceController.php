<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Source;

class SourceController extends Controller
{
    /**
     * Filter and display all sources, download a CSV file of the results (if the format is specified).
     */
    public function index(Request $request)
    {
        $sources = Source::all()->toQuery();
        if ($request->identifier != '') {
            $sources = $sources->where('identifier', 'LIKE', '%'.$request->identifier.'%');
        }
        if ($request->title != '') {
            $sources = $sources->where('title', 'LIKE', '%'.$request->title.'%');
        }
        if ($request->author != '') {
            $sources = $sources->where('author', 'LIKE', '%'.$request->author.'%');
        }
        $sources = $sources->orderBy('identifier');

        if (isset($request->format)) {
            $filename = 'sources_'.strval(rand()).'.csv';           // To prevent errors when two users attempt to download a file at the same time,
                                                                    // the files are given randomized names, preventing a colision.
            $columns = [__('resources.source_identifier'), __('resources.source_title'), __('resources.source_author')];
            $query = [$request->identifier, $request->title, $request->author];
            
            $file = fopen($filename, "w");
            fputcsv($file, $columns);
            fputcsv($file, $query);
            $sources_csv = $sources->get();
            foreach($sources_csv as $source) {
                fputcsv($file, [$source->identifier, $source->title, $source->author]);
            }
            fclose($file);

            $headers = [
                'Content-Type' => 'text/csv',
            ];
            return response()->download($filename, 'sources_'.now()->format('Y-m-d_H-i-s').'.csv', $headers)->deleteFileAfterSend(true);
        }

        $paginator = $sources->paginate(20);
        return view('sources.index', compact('paginator'));
    }

    /**
     * Show the form for creating a new source.
     */
    public function create()
    {
        $source = new Source();
        return view('sources.create', compact('source'));
    }

    /**
     * Store a newly created source in storage.
     */
    public function store(Request $request)
    {
        $request->validate( [
            'identifier' => 'required|max:16|unique:sources,identifier',
            'title' => 'required|max:255',
            'author' => 'max:64|nullable',
        ]);

        $source = new Source();
        $source->identifier = $request->identifier;
        $source->title = $request->title;
        $source->author = $request->author;
        $source->save();
        return redirect()->route('sources.show', $source->id);
    }

    /**
     * Display the specified source.
     */
    public function show(string $id)
    {
        $source = Source::find($id);
        if (!$source) {
            return redirect()->route('sources.index')->with('not-found', __('resources.none_single'));
        }

        // Calculates the index page the specified source is on (needed for a return link to the index).
        $source_ids = Source::all()->toQuery()->orderBy('identifier')->pluck('id');
        $i = 0;
        foreach ($source_ids as $source_id) {
            if ($id == $source_id) {
                $page = intval($i / 20) + 1;
                break;
            } else {
                $i = $i + 1;
            }
        }

        return view('sources.show', compact('source', 'page'));
    }

    /**
     * Show the form for editing the specified source.
     */
    public function edit(string $id)
    {
        $source = Source::find($id);
        if (!$source) {
            return redirect()->route('sources.index')->with('not-found', __('resources.none_single'));
        }
        return view('sources.edit', compact('source'));
    }

    /**
     * Update the specified resource in source.
     */
    public function update(Request $request, string $id)
    {
        $source = Source::find($id);
        if (!$source) {
            return redirect()->route('sources.index')->with('not-found', __('resources.none_single'));
        }

        $request->validate( [
            'identifier' => 'required|max:16|unique:sources,identifier,'.$id,
            'title' => 'required|max:255',
            'author' => 'max:64|nullable',
        ]);

        $source->identifier = $request->identifier;
        $source->title = $request->title;
        $source->author = $request->author;
        $source->save();
        return redirect()->route('sources.show', $source->id);
    }

    /**
     * Remove the specified source from storage.
     */
    public function destroy(string $id)
    {
        $source = Source::find($id);
        if (!$source) {
            return redirect()->route('sources.index')->with('not-found', __('resources.none_single'));
        }
        $source->delete();
        return redirect()->route('sources.index');
    }
}
