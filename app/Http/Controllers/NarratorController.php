<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Narrator;

class NarratorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $narrators = Narrator::all()->sortBy('fullname');
        return view('narrators.index', compact('narrators'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $narrator = new Narrator();
        if ($narrator->gender == null) { $narrator->gender = '?'; }
        return view('narrators.create', compact('narrator'));
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

        $narrator = new Narrator();
        $narrator->fullname = $request->fullname;
        $narrator->gender = $request->gender;
        if ($narrator->gender == '?') { $narrator->gender = null; }
        $narrator->save();
        return redirect()->route('narrators.show', $narrator->id);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $narrator = Narrator::findOrfail($id);
        return view('narrators.show', compact('narrator'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $narrator = Narrator::findOrfail($id);
        if ($narrator->gender == null) { $narrator->gender = '?'; }
        return view('narrators.edit', compact('narrator'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $narrator = Narrator::findOrfail($id);
        $request->validate([
            'fullname' => 'required',
            'gender' => 'in:M,F,?',
        ]);

        $narrator->fullname = $request->fullname;
        $narrator->gender = $request->gender;
        if ($narrator->gender == '?') { $narrator->gender = null; }
        $narrator->save();
        return redirect()->route('narrators.show', $narrator->id);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        Narrator::findOrfail($id)->delete();
        return redirect()->route('narrators.index');
    }
}
