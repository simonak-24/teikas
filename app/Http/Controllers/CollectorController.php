<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Collector;

class CollectorController extends Controller
{
    /**
     * Display a listing of the resource.
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
            $collectors = $collectors->withCount('legends')->orderBy('legends_count', $request->sort)->paginate(20);
            return view('collectors.index', compact('collectors'));
        }
        $collectors = $collectors->orderBy('fullname')->paginate(20);
        return view('collectors.index', compact('collectors'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $collector = new Collector();
        if ($collector->gender == null) { $collector->gender = '?'; }
        return view('collectors.create', compact('collector'));
    }

    /**
     * Store a newly created resource in storage.
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
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $collector = Collector::find($id);
        if (!$collector) {
            return redirect()->back()->with('not-found', __('resources.none_single'));
        }
        
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
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $collector = Collector::find($id);
        if (!$collector) {
            return redirect()->back()->with('not-found', __('resources.none_single'));
        }
        if ($collector->gender == null) { $collector->gender = '?'; }
        return view('collectors.edit', compact('collector'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $collector = Collector::find($id);
        if (!$collector) {
            return redirect()->back()->with('not-found', __('resources.none_single'));
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
     * Remove the specified resource from storage.
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
