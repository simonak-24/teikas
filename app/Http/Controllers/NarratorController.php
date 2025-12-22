<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Narrator;

class NarratorController extends Controller
{
    /**
     * Display a listing of the resource.
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
            $narrators = $narrators->withCount('legends')->orderBy('legends_count', $request->sort)->paginate(20);
            return view('narrators.index', compact('narrators'));
        }
        $narrators = $narrators->orderBy('fullname')->paginate(20);
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
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $narrator = Narrator::find($id);
        if (!$narrator) {
            return redirect()->back()->with('not-found', __('resources.none_single'));
        }

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
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $narrator = Narrator::find($id);
        if (!$narrator) {
            return redirect()->back()->with('not-found', __('resources.none_single'));
        }
        if ($narrator->gender == null) { $narrator->gender = '?'; }
        return view('narrators.edit', compact('narrator'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $narrator = Narrator::find($id);
        if (!$narrator) {
            return redirect()->back()->with('not-found', __('resources.none_single'));
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
     * Remove the specified resource from storage.
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
