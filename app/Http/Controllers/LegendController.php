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
    public function index()
    {
        $legends = Legend::orderBy('identifier')->paginate(20);
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
            'identifier' => 'max:9|regex:/^[0-9]+$/|required|unique:legends,identifier',
            'metadata' => 'max:255|required',
            'title_lv' => 'max:100|required',
            'title_de' => 'max:100|required',
            'text_lv' => 'required',
            'text_de' => 'required',
            'chapter_lv' => 'max:100|required',
            'chapter_de' => 'max:100|required',
            'volume' => 'max:2|regex:/^[0-9]+$/|required',
            'comments' => 'nullable',
            'external_identifier' => 'max:7|regex:/^[0-9]+$/|nullable',
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

        $legend->collector_id = $request->collector;
        $legend->narrator_id = $request->narrator;
        $legend->place_id = $request->place;
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

        $request->validate( [
            'identifier' => 'max:9|regex:/^[0-9]+$/|required|unique:legends,identifier,'.$legend->id,
            'metadata' => 'max:255|required',
            'title_lv' => 'max:100|required',
            'title_de' => 'max:100|required',
            'text_lv' => 'required',
            'text_de' => 'required',
            'chapter_lv' => 'max:100|required',
            'chapter_de' => 'max:100|required',
            'volume' => 'max:2|regex:/^[0-9]+$/|required',
            'comments' => 'nullable',
            'external_identifier' => 'max:7|regex:/^[0-9]+$/|nullable',
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

        $legend->collector_id = $request->collector;
        $legend->narrator_id = $request->narrator;
        $legend->place_id = $request->place;
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
        Legend::where('identifier', $id)->first()->delete();
        return redirect()->route('legends.index');
    }
}
