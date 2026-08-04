<?php
 
namespace App\Services;

use Illuminate\Http\Request;
use App\Models\Legend;
use App\Models\Collector;
use App\Models\Narrator;
use App\Models\Place;
use App\Models\Source;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;
 
class SortService
{
     /**
     * Sorts and filters legends according to the request passed by form.
     */
    public function sort(Request $request)
    {
        $sort = array();
        if (isset($request->sort)) {
            if ($request->sort == "cl") {
                if ($request->collector_sort == urlencode("⭥")) { 
                    $sort["collector"] = urlencode("⭡"); 
                } else if ($request->collector_sort == urlencode("⭡")) {
                    $sort["collector"] = urlencode("⭣");
                } else {
                    $sort["collector"] = urlencode("⭥");
                }
                $sort["narrator"] = urlencode("⭥");
                $sort["place"] = urlencode("⭥");
                $sort["sort"] = "cl";
            } else if ($request->sort == "nr") {
                $sort["collector"] = urlencode("⭥");
                if ($request->narrator_sort == urlencode("⭥")) { 
                    $sort["narrator"] = urlencode("⭡"); 
                } else if ($request->narrator_sort == urlencode("⭡")) {
                    $sort["narrator"] = urlencode("⭣");
                } else {
                    $sort["narrator"] = urlencode("⭥");
                }
                $sort["place"] = urlencode("⭥");
                $sort["sort"] = "nr";
            } else {
                $sort["collector"] = urlencode("⭥");
                $sort["narrator"] = urlencode("⭥");
                if ($request->place_sort == urlencode("⭥")) { 
                    $sort["place"] = urlencode("⭡"); 
                } else if ($request->place_sort == urlencode("⭡")) {
                    $sort["place"] = urlencode("⭣");
                } else {
                    $sort["place"] = urlencode("⭥");
                }
                $sort["sort"] = "pl";
            }
        } else {
            $sort["collector"] = $request->collector_sort;
            $sort["narrator"] = $request->narrator_sort;
            $sort["place"] = $request->place_sort;
            $sort["sort"] = "";
        }
        if ($sort["collector"] == urlencode("⭡")) {
            $legends = Legend::join('collectors', 'legends.collector_id', '=', 'collectors.id')->orderBy('collectors.fullname', 'asc');
        } else if ($sort["collector"] == urlencode("⭣")) {
            $legends = Legend::join('collectors', 'legends.collector_id', '=', 'collectors.id')->orderBy('collectors.fullname', 'desc');
        } else if ($sort["narrator"] == urlencode("⭡")) {
            $legends = Legend::join('narrators', 'legends.narrator_id', '=', 'narrators.id')->orderBy('narrators.fullname', 'asc');
        } else if ($sort["narrator"] == urlencode("⭣")) {
            $legends = Legend::join('narrators', 'legends.narrator_id', '=', 'narrators.id')->orderBy('narrators.fullname', 'desc');
        } else if ($sort["place"] == urlencode("⭡")) {
            $legends = Legend::join('places', 'legends.place_id', '=', 'places.id')->orderBy('places.name', 'asc');
        } else if ($sort["place"] == urlencode("⭣")) {
            $legends = Legend::join('places', 'legends.place_id', '=', 'places.id')->orderBy('places.name', 'desc');
        } else {
            $legends = Legend::orderBy('identifier');
        }

        if ($request->identifier != '') {
            $identifier_arrays = $this->fragment($request->identifier);
            $identifier_fragments = array_merge($identifier_arrays['quoted'], $identifier_arrays['unquoted']);
            foreach ($identifier_fragments as $fragment) {
                $legends = $legends->where('identifier', 'LIKE', '%'.$fragment.'%');
            }
        }
        if ($request->volume != '') {
            $volume_arrays = $this->fragment($request->volume);
            $volume_fragments = array_merge($volume_arrays['quoted'], $volume_arrays['unquoted']);
            foreach ($volume_fragments as $fragment) {
                $legends = $legends->where('volume', 'LIKE', '%'.$fragment.'%');
            }
        }
        if ($request->chapter != '') {
            $chapter_arrays = $this->fragment($request->chapter);
            $chapter_fragments = array_merge($chapter_arrays['quoted'], $chapter_arrays['unquoted']);
            foreach ($chapter_fragments as $fragment) {
                $legends = $legends->where('chapter_lv', 'LIKE', '%'.$fragment.'%');
            }
        }
        if ($request->title != '') {
            $title_arrays = $this->fragment($request->title);
            $title_fragments = array_merge($title_arrays['quoted'], $title_arrays['unquoted']);
            foreach ($title_fragments as $fragment) {
                $legends = $legends->where('title_lv', 'LIKE', '%'.$fragment.'%');
            }
        }

        if($request->text != '') {
            $text_arrays = $this->fragment($request->text);
            $text_fragments = array_merge($text_arrays['quoted'], $text_arrays['unquoted']);
            foreach($text_fragments as $fragment) {
                $legends = $legends->where('text_lv', 'LIKE', '%'.$fragment.'%');
            }
        }

        if ($request->collector != '') {
            $collector_arrays = $this->fragment($request->collector);
            $collector_fragments = array_merge($collector_arrays['quoted'], $collector_arrays['unquoted']);
            $collectors = Collector::orderBy('fullname');
            foreach($collector_fragments as $fragment) {
                $collectors = $collectors->where('fullname', 'LIKE', '%'.$fragment.'%');
            }
            $collectors = $collectors->pluck('id');
            $legends = $legends->whereIn('collector_id', $collectors);
        } else if ($request->origin == 'collector') {
            $legends = $legends->where('collector_id', $request->item_id);
        }
        if ($request->narrator != '') {
            $narrator_arrays = $this->fragment($request->narrator);
            $narrator_fragments = array_merge($narrator_arrays['quoted'], $narrator_arrays['unquoted']);
            $narrators = Narrator::orderBy('fullname');
            foreach($narrator_fragments as $fragment) {
                $narrators = $narrators->where('fullname', 'LIKE', '%'.$fragment.'%');
            }
            $narrators = $narrators->pluck('id');
            $legends = $legends->whereIn('narrator_id', $narrators);
        } else if ($request->origin == 'narrator') {
            $legends = $legends->where('narrator_id', $request->item_id);
        }
        if ($request->place != '') {
            $place_arrays = $this->fragment($request->place);
            $place_fragments = array_merge($place_arrays['quoted'], $place_arrays['unquoted']);
            $places = Place::orderBy('name');
            foreach($place_fragments as $fragment) {
                $places = $places->where('name', 'LIKE', '%'.$fragment.'%');
            }
            $places = $places->pluck('id');
            $legends = $legends->whereIn('place_id', $places);
        } else if ($request->origin == 'place') {
            $legends = $legends->where('place_id', $request->item_id);
        }

        if (isset($request->sources)) {
            $sources_selected = [];
            foreach($request->sources as $source) {
                $source_id = Source::where('identifier', $source)->first()->id;
                array_push($sources_selected, $source_id);
            }

            $legends = $legends->whereHas('sources', function(Builder $query) use ($sources_selected) {
                $query->whereIn('source_id', $sources_selected);
            });
        } else if ($request->origin == 'source') {
            $source_selected = [$request->item_id];
            $legends = $legends->whereHas('sources', function(Builder $query) use ($source_selected) {
                $query->whereIn('source_id', $source_selected);
            });
        }

        $sorted = array();
        $sorted['legends'] = $legends;
        $sorted['sort'] = $sort;
        return $sorted;
    }

    /**
     * Sorts and filters items according to the global search parameter.
     */
    public function global(mixed $items, string $type, string $global_string) {
        if (isset($items)) {
            $global_arrays = $this->fragment($global_string);   // TODO: papildināt / pārveidot, kad ievieš lemmatizāciju

            foreach (["quoted", "unquoted"] as $array_mode) {
                if ($type == 'legends') {
                    foreach ($global_arrays[$array_mode] as $global_fragment) {
                        $collectors_fullnames = Collector::orderBy('fullname')->whereLike('fullname', '%'.$global_fragment.'%')->pluck('id');
                        $narrators_fullnames = Narrator::orderBy('fullname')->whereLike('fullname', '%'.$global_fragment.'%')->pluck('id');
                        $places_names = Place::orderBy('name')->whereLike('name', '%'.$global_fragment.'%')->pluck('id');
                        $sources_identifiers = Source::orderBy('identifier')->whereLike('identifier', '%'.$global_fragment.'%')
                                                    ->orWhereLike('title', '%'.$global_fragment.'%')
                                                    ->pluck('id');

                        $legends = Legend::orderBy('identifier')
                                        ->whereLike('identifier', '%'.$global_fragment.'%')
                                        ->orWhereLike('volume', '%'.$global_fragment.'%')
                                        ->orWhereLike('chapter_lv', '%'.$global_fragment.'%')
                                        ->orWhereLike('title_lv', '%'.$global_fragment.'%')
                                        ->orWhereLike('text_lv', '%'.$global_fragment.'%')
                                        ->orWhereIn('collector_id', $collectors_fullnames)
                                        ->orWhereIn('narrator_id', $narrators_fullnames)
                                        ->orWhereIn('place_id', $places_names)
                                        ->orWhereHas('sources', function(Builder $query) use ($sources_identifiers) {
                                            $query->whereIn('source_id', $sources_identifiers);
                                        })->pluck('id');
                        $items = $items->whereIn('legends.id', $legends);
                    }
                } else if ($type == 'collectors') {
                    foreach ($global_arrays[$array_mode] as $global_fragment) {
                        $collectors = Collector::orderBy('fullname')
                                                ->whereLike('fullname', '%'.$global_fragment.'%')
                                                ->pluck('id');
                        $items = $items->whereIn('collectors.id', $collectors);
                    }
                }  else if ($type == 'narrators') {
                    foreach ($global_arrays[$array_mode] as $global_fragment) {
                        $narrators = Narrator::orderBy('fullname')
                                            ->whereLike('fullname', '%'.$global_fragment.'%')
                                            ->pluck('id');
                        $items = $items->whereIn('narrators.id', $narrators);
                    }
                } else if ($type == 'places') {
                    foreach ($global_arrays[$array_mode] as $global_fragment) {
                        $places = Place::orderBy('name')
                                        ->whereLike('name', '%'.$global_fragment.'%')
                                        ->pluck('id');
                        $items = $items->whereIn('places.id', $places);
                    }
                } else if ($type == 'sources') {
                    foreach ($global_arrays[$array_mode] as $global_fragment) {
                        $sources = Source::orderBy('identifier')
                                        ->whereLike('identifier', '%'.$global_fragment.'%')
                                        ->orWhereLike('title', '%'.$global_fragment.'%')
                                        ->orWhereLike('author', '%'.$global_fragment.'%')
                                        ->pluck('id');
                        $items = $items->whereIn('sources.id', $sources);
                    }
                }
            }
        }
        return $items;
    }

    /**
     * Highlights all instances of the given fragments in a list of items.
     */
    public function highlight(mixed $items, string $type, string $global, Request $request) {
        if (isset($items)) {
            if ($type == 'legends') {
                foreach ($items as $legend) {
                    if ($request->identifier != '') {
                        $legend->identifier = $this->bold($legend->identifier, $global.' '.$request->identifier);
                    } else {
                        $legend->identifier = $this->bold($legend->identifier, $global);
                    }
                    if ($request->title != '') {
                        $legend->title_lv = $this->bold($legend->title_lv, $global.' '.$request->title);
                    } else {
                        $legend->title_lv = $this->bold($legend->title_lv, $global);
                    }
                    if ($request->chapter != '') {
                        $legend->chapter_lv = $this->bold($legend->chapter_lv, $global.' '.$request->chapter);
                    } else {
                        $legend->chapter_lv = $this->bold($legend->chapter_lv, $global);
                    }
                    if ($request->volume != '') {
                        $legend->volume = $this->bold($legend->volume, $global.' '.$request->volume);
                    } else {
                        $legend->volume = $this->bold($legend->volume, $global);
                    }

                    if ($request->collector != '') {
                        $legend->collector->fullname = $this->bold($legend->collector->fullname, $global.' '.$request->collector);
                    } else {
                        $legend->collector->fullname = $this->bold($legend->collector->fullname, $global);
                    }
                    if ($request->narrator != '') {
                        $legend->narrator->fullname = $this->bold($legend->narrator->fullname, $global.' '.$request->narrator);
                    } else {
                        $legend->narrator->fullname = $this->bold($legend->narrator->fullname, $global);
                    }
                    if ($request->place != '') {
                        $legend->place->name = $this->bold($legend->place->name, $global.' '.$request->place);
                    } else {
                        $legend->place->name = $this->bold($legend->place->name, $global);
                    }
                }
            } else if ($type == 'collectors' || $type == 'narrators') {
                foreach ($items as $person) {
                    if ($request->fullname != '') {
                        $person->fullname = $this->bold($person->fullname, $global.' '.$request->fullname);
                    } else {
                        $person->fullname = $this->bold($person->fullname, $global);
                    }
                }
            } else if ($type == 'places') {
                foreach ($items as $place) {
                    if ($request->name != '') {
                        $place->name = $this->bold($place->name, $global.' '.$request->name);
                    } else {
                        $place->name = $this->bold($place->name, $global);
                    }
                }
            } else if ($type == 'sources') {
                foreach ($items as $source) {
                    if ($request->identifier != '') {
                        $source->identifier = $this->bold($source->identifier, $global.' '.$request->identifier);
                    } else {
                        $source->identifier = $this->bold($source->identifier, $global);
                    }
                    if ($request->title != '') {
                        $source->title = $this->bold($source->title, $global.' '.$request->title);
                    } else {
                        $source->title = $this->bold($source->title, $global);
                    }
                    if ($request->author != '') {
                        $source->author = $this->bold($source->author, $global.' '.$request->author);
                    } else {
                        $source->author = $this->bold($source->author, $global);
                    }
                }
            }
        }
        
        return $items;
    }

    /**
     * Highlights any given instance of the given fragment in the given text.
     */
    public function bold(string $text, string $fragment_string)
    {
        $global_arrays = $this->fragment($fragment_string);
        $global_all = array_merge($global_arrays['quoted'], $global_arrays['unquoted']);
        foreach ($global_all as $fragment) {
            $text_bolded = $this->intervals($text, $fragment);
            $text_bolded = $this->merge($text_bolded);
            if (count($text_bolded) > 0) {
                $offset = 0;
                foreach ($text_bolded as $bold) {
                    $start_pos = $bold['start'] + $offset;
                    $length = $bold['end'] - $bold['start'];
                    $text = mb_substr($text, 0, $start_pos)."<b>".mb_substr($text, $start_pos, $length)."</b>".mb_substr($text, $start_pos + $length);
                    $offset = $offset + mb_strlen("<b></b>");
                }
            }
        }
        return $text;
    }

     /**
     * Highlights and shortens legend texts according to the given text fragment.
     */
    public function text(mixed $legends, string $text)
    {
        $text_frag = $this->clean($text);
        if ($text != '') {
            $global_arrays = $this->fragment($text);
            $global_all = array_merge($global_arrays['quoted'], $global_arrays['unquoted']);    // TODO: uzlabot, kad ievieš lemmatizāciju
            foreach ($legends as $legend) {
                $legend_text = $this->clean($legend->text_lv);
                $legend_bolded = array();
                foreach ($global_all as $frag) {
                    $legend_bolded = array_merge($legend_bolded, $this->intervals($legend_text, $frag));
                }
                $legend_bolded = $this->merge($legend_bolded);

                $offset = 0;
                $cutoff = 0;
                foreach ($legend_bolded as $interval) {
                    $start_pos = $interval['start'] + $offset;
                    $length = $interval['end'] - $interval['start'];
                if (isset($legend->text)) {
                    $start_pos = $start_pos - $cutoff;
                    $legend->text = mb_substr($legend->text, 0, $start_pos)."<b>".mb_substr($legend->text, $start_pos, $length)."</b>".mb_substr($legend->text, $start_pos + $length);
                } else {
                    $border_limit = intval((100 - $length) / 2);
                    if(mb_strlen($legend->text_lv) > 100) {
                        if ($start_pos < $border_limit) {
                            $legend->text = mb_substr($legend->text_lv, 0, $start_pos)."<b>".mb_substr($legend->text_lv, $start_pos, $length)."</b>".mb_substr($legend->text_lv, $start_pos + $length, 100 - ($start_pos + $length))."...";
                        } else if ($start_pos > mb_strlen($legend->text_lv) - $border_limit) {
                            $legend->text = "...".mb_substr($legend->text_lv, mb_strlen($legend->text_lv) - 100, $start_pos - (mb_strlen($legend->text_lv) - 100))."<b>".mb_substr($legend->text_lv, $start_pos, $length)."</b>".mb_substr($legend->text_lv, $start_pos + $length);
                            $offset = $offset + mb_strlen("...");
                            $cutoff = mb_strlen($legend->text_lv) - 100;
                        } else {
                            $legend->text = "...".mb_substr($legend->text_lv, $start_pos - $border_limit, $border_limit)."<b>".mb_substr($legend->text_lv, $start_pos, $length)."</b>".mb_substr($legend->text_lv, $start_pos + $length, 100 - ($border_limit + $length))."...";
                            $offset = $offset + mb_strlen("...");
                            $cutoff = $start_pos - $border_limit;
                        }
                    } else {
                        $legend->text = mb_substr($legend->text_lv, 0, $start_pos)."<b>1".mb_substr($legend->text_lv, $start_pos, $length)."</b>".mb_substr($legend->text_lv, $start_pos + $length);
                    }
                    
                }
                    $offset = $offset + mb_strlen("<b></b>");
                }
            }
        }

        foreach ($legends as $legend) {
            if (!isset($legend->text)) {
                $legend->text = Str::limit($legend->text_lv, 100);
            }
        }
        return $legends;
    }

    /**
     * Cleans the given text by turning it lowercase and removing diacritical marks.
     */
    public function clean(string $text) {
        $text = mb_strtolower($text);
        $replacements = array("ā"=>"a", "č"=>"c", "ē"=>"e", "ģ"=>"g", "ī"=>"i", "ķ"=>"k", "ļ"=>"l", "ņ"=>"n", "ŗ"=>"r", "š"=>"s", "ū"=>"u", "ž"=>"z");
        foreach ($replacements as $search => $replace) { $text = mb_eregi_replace($search, $replace, $text); }

        return $text;
    }

    /**
     * Seperates a string into fragments used for filtering.
     */
    public function fragment(string $string) {
        $processed = array();

        if($string != '') {
            $unprocessed = $string;
        } else {
            $unprocessed = '';
        }
        $quoted = array();
        $processed = "";

        while (gettype(mb_strpos($unprocessed, "'")) == "integer" || gettype(mb_strpos($unprocessed, '"')) == "integer") {
            if (gettype(mb_strpos($unprocessed, "'")) == "integer" && gettype(mb_strpos($unprocessed, '"')) != "integer") {
                $first_pos = mb_strpos($unprocessed, "'");
                $second_pos = mb_strpos(mb_substr($unprocessed, $first_pos + 1), "'");
            } else if (gettype(mb_strpos($unprocessed, '"')) == "integer" && gettype(mb_strpos($unprocessed, "'")) != "integer") {
                $first_pos = mb_strpos($unprocessed, '"');
                $second_pos = mb_strpos(mb_substr($unprocessed, $first_pos + 1), '"');
            } else {
                if (mb_strpos($unprocessed, "'") < mb_strpos($unprocessed, '"')) {
                    $first_pos = mb_strpos($unprocessed, "'");
                    $second_pos = mb_strpos(mb_substr($unprocessed, $first_pos + 1), "'");
                } else {
                    $first_pos = mb_strpos($unprocessed, '"');
                    $second_pos = mb_strpos(mb_substr($unprocessed, $first_pos + 1), '"');
                }
            }

            $processed = $processed.mb_substr($unprocessed, 0, $first_pos);
            if (gettype($second_pos) != "integer") {
                $unprocessed = mb_substr($unprocessed, $first_pos + 1);
                continue;
            } else {
                $second_pos = $second_pos + $first_pos + 1;
            }
            $substr_length = $second_pos - $first_pos - 1;
            array_push($quoted, mb_substr($unprocessed, $first_pos + 1, $substr_length));
            $unprocessed = mb_substr($unprocessed, $second_pos + 1);
        }
        $processed = $processed.$unprocessed;

        $unquoted = explode(" ", $processed);
        $final = count($unquoted);
        for ($i = 0; $i < $final; $i = $i + 1) {
            if ($unquoted[$i] == "") {
                unset($unquoted[$i]);
            } else {
                $unquoted[$i] = $this->clean($unquoted[$i]);
            }
        }

        $fragmented = array();
        $fragmented['quoted'] = array_unique($quoted);
        $fragmented['unquoted'] = array_unique($unquoted);
        return $fragmented;
    }

    /**
     * Finds all instances of fragments in a string, returning it as an array of intervals of start and end symbol positions.
     */
    public function intervals(string $string, string $frag) {
        $intervals = array();
        $temp_text = $this->clean($string);
        while (gettype(mb_strpos($temp_text, $frag)) == 'integer') {
            $first_pos = mb_strpos($temp_text, $frag);
            if ($frag == 'b') {
                if ($first_pos > 0) {
                    if ($temp_text[$first_pos - 1] == '<' || $temp_text[$first_pos - 1] == '/') {
                        $temp_text = mb_substr($temp_text, 0, $first_pos).str_pad("", 1, "#").mb_substr($temp_text, $first_pos + 1);
                        continue;
                    }
                }
            }
            $length = mb_strlen($frag);
            $second_pos = $first_pos + $length;
            array_push($intervals, ['start' => $first_pos, 'end' => $second_pos]);
            $temp_text = mb_substr($temp_text, 0, $first_pos).str_pad("", $length, "#").mb_substr($temp_text, $second_pos);
        }
        return $intervals;
    }

    /**
     * Merges intervals, preventing overlap between them. Intervals must be arrays of 2 integers, accessed by keys 'start' and 'end'.
     */
    public function merge(mixed $intervals) {
        $start = array_column($intervals, 'start');
        array_multisort($start, SORT_ASC, $intervals);              // Sorts the intervals by their starting positions.
        
        $overlap = true;
        while ($overlap) {
            $overlap = false;
            $intervals_copy = array();
            $elim = -1;
            for ($i = 0, $length = count($intervals); $i < $length; $i++) {
                if (!$overlap) {
                    for ($j = $i + 1; $j < $length; $j++) {
                        if ($intervals[$i]['end'] >= $intervals[$j]['start']) {
                            if ($intervals[$i]['end'] > $intervals[$j]['end']) {                                // The end of the current overlaps with the compared. Other case is impossible, as the sections are sorted.
                                array_push($intervals_copy, ['start' => $intervals[$i]['start'], 'end' => $intervals[$i]['end']]);     // The current entirely overlaps with the compared.
                            } else {
                                array_push($intervals_copy, ['start' => $intervals[$i]['start'], 'end' => $intervals[$j]['end']]);     // The current ends before the compared does.
                            }
                            $elim = $j;
                            $overlap = true;
                            break;
                        }
                    }
                    if (!$overlap) {
                        array_push($intervals_copy, ['start' => $intervals[$i]['start'], 'end' => $intervals[$i]['end']]);
                    }
                } else {
                    if ($i != $elim) {
                        array_push($intervals_copy, ['start' => $intervals[$i]['start'], 'end' => $intervals[$i]['end']]);
                    }
                }
            }
            $intervals = $intervals_copy;
        }

        return $intervals;
    }
}