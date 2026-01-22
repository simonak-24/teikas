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
        $exists = False;
        return view('sources.edit', compact('source', 'exists'));
    }

    /**
     * Store a newly created source in storage.
     */
    public function store(Request $request)
    {
        $source = new Source();
        
        $this->save($request, $source, False);
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
        $source_ids = Source::all()->toQuery()->orderBy('identifier')->pluck('id')->toArray();
        $i = array_search($source->id, $source_ids);
        $page = intval($i / 20) + 1;

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
        $exists = True;
        return view('sources.edit', compact('source', 'exists'));
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

        $this->save($request, $source, True);
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

    /**
     * Save a source's data in storage.
     */
    public function save(Request $request, Source $source, bool $edit)
    {
        if ($edit) {
            $extra_rule = ','.$source->id;
        } else {
            $extra_rule = '';
        }

        $request->validate( [
            'identifier' => 'required|max:16|unique:sources,identifier'.$extra_rule,
            'title' => 'required|max:255',
            'author' => 'max:64|nullable',
        ]);

        $source->identifier = $request->identifier;
        $source->title = $request->title;
        $source->author = $request->author;
        $source->save();
    }
}
