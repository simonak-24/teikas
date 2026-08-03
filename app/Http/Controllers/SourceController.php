<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Source;
use App\Services\SortService;

class SourceController extends Controller
{
    protected $sortService;

    public function __construct(SortService $sortService)
    {
        $this->sortService = $sortService;
    }

    /**
     * Filter and display all sources, download a CSV file of the results (if the format is specified).
     */
    public function index(Request $request)
    {
        $sources = Source::all()->toQuery();
        if ($request->global_search != '') {
            $sources = $this->sortService->global($sources, 'sources', $request->global_search);
        }

        if ($request->identifier != '') {
            $sources = $sources->where('identifier', 'LIKE', '%'.$request->identifier.'%');
        }
        if ($request->title != '') {
            $sources = $sources->where('title', 'LIKE', '%'.$request->title.'%');
        }
        if ($request->author != '') {
            $sources = $sources->where('author', 'LIKE', '%'.$request->author.'%');
        }
        if ($request->sort != '') {
            $sources = $sources->withCount('legends')->orderBy('legends_count', $request->sort);
        } else {
            $sources = $sources->orderBy('identifier');
        }

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

        $sources = $sources->paginate(app('items_per_page'));
        if ($request->global_search != '') {
            $sources = $this->sortService->highlight($sources, 'sources', $request->global_search, $request);
        }
        return view('sources.index', compact('sources'));
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
    public function show(string $id, Request $request)
    {
        $source = Source::find($id);
        if (!$source) {
            return redirect()->route('sources.index')->with('not-found', __('resources.none_single'));
        }
        $item = $source;

        $request->origin = "source";
        $request->item_id = $id;
        $sorted = $this->sortService->sort($request);
        $paginator = $sorted['legends']->paginate(app('items_per_page'));
        $sort = $sorted['sort'];

        // Calculates the index page the specified source is on (needed for a return link to the index).
        $source_ids = Source::all()->toQuery()->orderBy('identifier')->pluck('id')->toArray();
        $i = array_search($source->id, $source_ids);
        $page = intval($i / 20) + 1;

        $paginator = $this->sortService->text($paginator, isset($request->text) ? $request->text : '');
        return view('sources.show', compact('source', 'page', 'paginator', 'sort', 'item'));
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
