<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Collector;

class CollectorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $collectors = Collector::all()->sortBy('fullname');
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
            'fullname' => 'required',
            'gender' => 'in:M,F,?',
        ]);

        $collector = new Collector();
        $collector->fullname = $request->fullname;
        $collector->gender = $request->gender;
        if ($collector->gender == '?') { $collector->gender = null; }
        $collector->save();
        return redirect()->route('collectors.show', $collector->id);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $collector = Collector::findOrfail($id);
        return view('collectors.show', compact('collector'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $collector = Collector::findOrfail($id);
        if ($collector->gender == null) { $collector->gender = '?'; }
        return view('collectors.edit', compact('collector'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $collector = Collector::findOrfail($id);
        $request->validate([
            'fullname' => 'required',
            'gender' => 'in:M,F,?',
        ]);

        $collector->fullname = $request->fullname;
        $collector->gender = $request->gender;
        if ($collector->gender == '?') { $collector->gender = null; }
        $collector->save();
        return redirect()->route('collectors.show', $collector->id);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        Collector::findOrfail($id)->delete();
        return redirect()->route('collectors.index');
    }
}
