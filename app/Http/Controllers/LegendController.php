<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Legend;
use App\Models\Collector;
use App\Models\Narrator;
use App\Models\Place;
use App\Models\Source;
use App\Models\LegendSource;

class LegendController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $legends = Legend::orderBy('identifier');
        if ($request->identifier != '') {
            $legends = $legends->where('identifier', 'LIKE', '%'.$request->identifier.'%');
        }
        if ($request->volume != '') {
            $legends = $legends->where('volume', 'LIKE', '%'.$request->volume.'%');
        }
        if ($request->chapter != '') {
            $legends = $legends->where('chapter_lv', 'LIKE', '%'.$request->chapter.'%');
        }
        if ($request->title != '') {
            $legends = $legends->where('title_lv', 'LIKE', '%'.$request->title.'%');
        }
        if ($request->text != '') {
            $legends = $legends->where('text_lv', 'LIKE', '%'.$request->text.'%');
        }
        if ($request->collector != '') {
            $collectors = Collector::orderBy('fullname')->where('fullname', 'LIKE', '%'.$request->collector.'%')->pluck('id');
            $legends = $legends->whereIn('collector_id', $collectors);
        }
        if ($request->narrator != '') {
            $narrators = Narrator::orderBy('fullname')->where('fullname', 'LIKE', '%'.$request->narrator.'%')->pluck('id');
            $legends = $legends->whereIn('narrator_id', $narrators);
        }
        if ($request->place != '') {
            $places = Place::orderBy('name')->where('name', 'LIKE', '%'.$request->place.'%')->pluck('id');
            $legends = $legends->whereIn('place_id', $places);
        }
        $legends = $legends->paginate(20);
        return view('legends.index', compact('legends'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $legend = new Legend();

        $collectors = Collector::all()->sortBy('fullname');
        $collectors_search = json_encode($collectors->toArray());
        $narrators = Narrator::all()->sortBy('fullname');
        $narrators_search = json_encode($narrators->toArray());
        $places = Place::all()->sortBy('name');
        $places_search = json_encode($places->toArray());
        $sources = Source::all()->sortBy('identifier');
        $sources_search = json_encode($sources->toArray());

        $selected = array();
        foreach ($legend->sources as $source) {
            array_push($selected, $source->source->id);
        }
        $sources_selected = $selected;

        return view('legends.create', compact('legend', 'collectors', 'collectors_search', 'narrators', 'narrators_search', 'places', 'places_search', 'sources', 'sources_search', 'sources_selected'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate( [
            'identifier' => 'required|max:9|regex:/^[0-9]+$/|unique:legends,identifier',
            'metadata' => 'required|max:255',
            'title_lv' => 'required|max:100',
            'title_de' => 'required|max:100',
            'text_lv' => 'required',
            'text_de' => 'required',
            'chapter_lv' => 'required|max:100',
            'chapter_de' => 'required|max:100',
            'volume' => 'required|max:2|regex:/^[0-9]+$/',
            'comments' => 'nullable',
            'external_id' => 'max:7|regex:/^[0-9]+$/|nullable',
        ]);

        $legend = new Legend();
        $legend->identifier = $request->identifier;
        $legend->metadata = $request->metadata;
        $legend->title_lv = $request->title_lv;
        $legend->title_de = $request->title_de;
        $legend->text_lv = $request->text_lv;
        $legend->text_de = $request->text_de;
        $legend->chapter_lv = $request->chapter_lv;
        $legend->chapter_de = $request->chapter_de;
        $legend->volume = $request->volume;
        $legend->comments = $request->comments;

        $legend->collector_id = $request->collector_id;
        $legend->narrator_id = $request->narrator_id;
        $legend->place_id = $request->place_id;
        $legend->external_identifier = $request->external_id;
        $legend->save();

        if (isset($request->sources)) {
            foreach($request->sources as $source){
                $link = new LegendSource();
                $link->legend_id = $legend->id;
                $link->source_id = $source;
                $link->save();
            }
        }
        return redirect()->route('legends.show', $legend->identifier);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $legend = Legend::where('identifier', $id)->first();
        if (!$legend) {
            return redirect()->back()->with('not-found', __('resources.none_single'));
        }

        $legend_ids = Legend::all()->sortBy('identifier')->pluck('identifier');
        $i = 0;
        foreach($legend_ids as $legend_id) {
            if ($legend->identifier == $legend_id) {
                $page = intval($i / 20) + 1;
                break;
            } else {
                $i = $i + 1;
            }
        }

        return view('legends.show', compact('legend', 'page'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $legend = Legend::where('identifier', $id)->first();
        if (!$legend) {
            return redirect()->back()->with('not-found', __('resources.none_single'));
        }

        $collectors = Collector::all()->sortBy('fullname');
        $collectors_search = json_encode($collectors->toArray());
        $narrators = Narrator::all()->sortBy('fullname');
        $narrators_search = json_encode($narrators->toArray());
        $places = Place::all()->sortBy('name');
        $places_search = json_encode($places->toArray());
        $sources = Source::all()->sortBy('identifier');
        $sources_search = json_encode($sources->toArray());

        $selected = array();
        foreach ($legend->sources as $source) {
            array_push($selected, $source->source->id);
        }
        $sources_selected = $selected;
        
        return view('legends.edit', compact('legend', 'collectors', 'collectors_search', 'narrators', 'narrators_search', 'places', 'places_search', 'sources', 'sources_search', 'sources_selected'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $legend = Legend::where('identifier', $id)->first();
        if (!$legend) {
            return redirect()->back()->with('not-found', __('resources.none_single'));
        }

        $request->validate( [
            'identifier' => 'required|max:9|regex:/^[0-9]+$/|unique:legends,identifier,'.$legend->id,
            'metadata' => 'required|max:255',
            'title_lv' => 'required|max:100',
            'title_de' => 'required|max:100',
            'text_lv' => 'required',
            'text_de' => 'required',
            'chapter_lv' => 'required|max:100',
            'chapter_de' => 'required|max:100',
            'volume' => 'required|max:2|regex:/^[0-9]+$/',
            'comments' => 'nullable',
            'external_id' => 'max:7|regex:/^[0-9]+$/|nullable',
        ]);

        $legend->identifier = $request->identifier;
        $legend->metadata = $request->metadata;
        $legend->title_lv = $request->title_lv;
        $legend->title_de = $request->title_de;
        $legend->text_lv = $request->text_lv;
        $legend->text_de = $request->text_de;
        $legend->chapter_lv = $request->chapter_lv;
        $legend->chapter_de = $request->chapter_de;
        $legend->volume = $request->volume;
        $legend->comments = $request->comments;

        $legend->collector_id = $request->collector_id;
        $legend->narrator_id = $request->narrator_id;
        $legend->place_id = $request->place_id;
        $legend->external_identifier = $request->external_id;
        $legend->save();

        $old_sources = LegendSource::where('legend_id', $legend->id)->get();
        foreach ($old_sources as $old_source) {
            $source = LegendSource::find($old_source->id)->delete();
        }

        if (isset($request->sources)) {
            foreach($request->sources as $source){
                $link = new LegendSource();
                $link->legend_id = $legend->id;
                $link->source_id = $source;
                $link->save();
            }
        }

        return redirect()->route('legends.show', $legend->identifier);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $legend = Legend::where('identifier', $id)->first();
        if (!$legend) {
            return redirect()->route('legends.index')->with('not-found', __('resources.none_single'));
        }
        $legend->delete();
        return redirect()->route('legends.index');
    }

    /**
     * Display full table of contents.
     */
    public function contents()
    {
        $chapters = [];
        $subchapters = [];
        $volumes_query = Legend::select('volume')->distinct('volume')->get();
        foreach($volumes_query as $volume) {
            $chapters[$volume['volume']] = [];
            $subchapters[$volume['volume']] = [];
        }

        $chapters_query = Legend::select('volume', 'chapter_lv', 'chapter_de', 'title_lv', 'title_de')->distinct('title_lv')->get();
        foreach($chapters_query as $chapter) {
            if (!(in_array([$chapter['chapter_lv'], $chapter['chapter_de']], $chapters[$chapter['volume']]))) {
                array_push($chapters[$chapter['volume']], [$chapter['chapter_lv'], $chapter['chapter_de']]);
                $subchapters[$chapter['volume']][$chapter['chapter_lv']] = [];
            }
            if ($chapter['chapter_lv'] != str_replace('ŗ', 'r', $chapter['title_lv'])) {
                array_push($subchapters[$chapter['volume']][$chapter['chapter_lv']], [$chapter['title_lv'], $chapter['title_de']]);
            }
        }
        return view('navigation.contents', compact('chapters', 'subchapters'));
    }

    /**
     * Display table of contents for a single chapter.
     */
    public function chapter(string $chapter)
    {
        $chapter_clean = urldecode($chapter);
        $legends = Legend::where('chapter_lv', $chapter_clean)->paginate(20);
        if ($legends->total() == 0) {
            return redirect()->back()->with('not-found', __('resources.none_single'));
        }
        return view('navigation.chapter', compact('legends'));
    }

    /**
     * Display table of contents for a single subchapter.
     */
    public function subchapter(string $chapter, string $subchapter)
    {
        $subchapter_clean = urldecode($subchapter);
        $legends = Legend::where('title_lv', $subchapter_clean)->paginate(20);
        if ($legends->total() == 0) {
            return redirect()->back()->with('not-found', __('resources.none_single'));
        }
        return view('navigation.subchapter', compact('legends'));
    }
}
