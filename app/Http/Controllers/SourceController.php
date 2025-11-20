<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Source;

class SourceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $sources = Source::all()->toQuery()->orderBy('identifier')->paginate(20);
        return view('sources.index', compact('sources'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $source = new Source();
        return view('sources.create', compact('source'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate( [
            'identifier' => 'max:16|required|unique:sources,identifier',
            'title' => 'max:255|required',
            'author' => 'max:16|nullable',
        ]);

        $source = new Source();
        $source->identifier = $request->identifier;
        $source->title = $request->title;
        $source->author = $request->author;
        $source->save();
        return redirect()->route('sources.show', $source->id);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $source = Source::findOrfail($id);

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
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $source = Source::findOrfail($id);
        return view('sources.edit', compact('source'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $source = Source::findOrfail($id);
        $request->validate( [
            'identifier' => 'max:16|required|unique:sources,identifier,'.$id,
            'title' => 'max:255|required',
            'author' => 'max:64|nullable',
        ]);

        $source->identifier = $request->identifier;
        $source->title = $request->title;
        $source->author = $request->author;
        $source->save();
        return redirect()->route('sources.show', $source->id);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        Source::findOrfail($id)->delete();
        return redirect()->route('sources.index');
    }
}
