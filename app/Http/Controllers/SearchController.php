<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Legend;
use App\Models\Collector;
use App\Models\Narrator;
use App\Models\Place;
use App\Models\Source;
use Illuminate\Database\Eloquent\Builder;
use App\Services\SortService;

class SearchController extends Controller
{
    protected $sortService;

    public function __construct(SortService $sortService)
    {
        $this->sortService = $sortService;
    }

    /**
     * Show the form for creating a new legend.
     */
    public function preview(Request $request)
    {
        $fragment = $request->global_search;
        if ($fragment == null) { $fragment = ""; }
        $preview_count = 5;

        $legends = Legend::orderBy('identifier');
        $legends = $this->sortService->global($legends, 'legends', $fragment);
        $paginator = $legends->paginate($preview_count);
        $paginator = $this->sortService->highlight($paginator, 'legends', $fragment);
        $paginator = $this->sortService->text($paginator, $fragment);

        $collectors = Collector::orderBy('fullname');
        $collectors = $this->sortService->global($collectors, 'collectors', $fragment);
        $collectors = $collectors->paginate($preview_count);
        $collectors = $this->sortService->highlight($collectors, 'collectors', $fragment);

        $narrators = Narrator::orderBy('fullname');
        $narrators = $this->sortService->global($narrators, 'narrators', $fragment);
        $narrators = $narrators->paginate($preview_count);
        $narrators = $this->sortService->highlight($narrators, 'narrators', $fragment);

        $places = Place::orderBy('name');
        $places = $this->sortService->global($places, 'places', $fragment);
        $places = $places->paginate($preview_count);
        $places = $this->sortService->highlight($places, 'places', $fragment);

        $sources = Source::orderBy('identifier');
        $sources = $this->sortService->global($sources, 'sources', $fragment);
        $sources = $sources->paginate($preview_count);
        $sources = $this->sortService->highlight($sources, 'sources', $fragment);
        
        $item = $fragment;
        $search = $request->search;
        return view('search', compact('paginator', 'collectors', 'narrators', 'places', 'sources', 'item', 'search'));
    }
}
