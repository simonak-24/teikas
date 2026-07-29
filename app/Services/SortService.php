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

        if($request->text != '') {
            $fragments = $this->fragment($request->text);
            foreach($fragments['unquoted'] as $unquoted) {
                $legends = $legends->where('text_lv', 'LIKE', '%'.$unquoted.'%');
            }
            foreach($fragments['quoted'] as $quoted) {
                $legends = $legends->where('text_lv', 'LIKE', '%'.$quoted.'%');
            }
        }

        if ($request->collector != '') {
            $collectors = Collector::orderBy('fullname')->where('fullname', 'LIKE', '%'.$request->collector.'%')->pluck('id');
            $legends = $legends->whereIn('collector_id', $collectors);
        } else if ($request->origin == 'collector') {
            $legends = $legends->where('collector_id', $request->item_id);
        }
        if ($request->narrator != '') {
            $narrators = Narrator::orderBy('fullname')->where('fullname', 'LIKE', '%'.$request->narrator.'%')->pluck('id');
            $legends = $legends->whereIn('narrator_id', $narrators);
        } else if ($request->origin == 'narrator') {
            $legends = $legends->where('narrator_id', $request->item_id);
        }
        if ($request->place != '') {
            $places = Place::orderBy('name')->where('name', 'LIKE', '%'.$request->place.'%')->pluck('id');
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
            $global_arrays = $this->fragment($global_string);                                   // TODO: papildināt, kad ievieš lemmatizāciju
            $global_all = array_merge($global_arrays['quoted'], $global_arrays['unquoted']);    // TODO: uzlabot, kad ir skaidrs, ko darīt ar izcelšanu
            foreach($global_all as $global) {
            if ($type == 'legends') {
                $collectors_fullnames = Collector::orderBy('fullname')->where('fullname', 'LIKE', '%'.$global.'%')->pluck('id');
                $narrators_fullnames = Narrator::orderBy('fullname')->where('fullname', 'LIKE', '%'.$global.'%')->pluck('id');
                $places_names = Place::orderBy('name')->where('name', 'LIKE', '%'.$global.'%')->pluck('id');
                $sources_identifiers = Source::orderBy('identifier')->where('identifier', 'LIKE', '%'.$global.'%')
                                    ->orWhere('title', 'LIKE', '%'.$global.'%')
                                    ->pluck('id');

                $legends = Legend::orderBy('identifier')
                    ->where('identifier', 'LIKE', '%'.$global.'%')
                    ->orWhere('volume', 'LIKE', '%'.$global.'%')
                    ->orWhere('chapter_lv', 'LIKE', '%'.$global.'%')
                    ->orWhere('title_lv', 'LIKE', '%'.$global.'%')
                    ->orWhere('text_lv', 'LIKE', '%'.$global.'%')
                    ->orWhereIn('collector_id', $collectors_fullnames)
                    ->orWhereIn('narrator_id', $narrators_fullnames)
                    ->orWhereIn('place_id', $places_names)
                    ->orWhereHas('sources', function(Builder $query) use ($sources_identifiers) {
                        $query->whereIn('source_id', $sources_identifiers);
                    })->pluck('id');

                $items = $items->whereIn('id', $legends);
            } else if ($type == 'collectors') {
                $collectors = Collector::orderBy('fullname')
                                ->where('fullname', 'LIKE', '%'.$global.'%')
                                ->pluck('id');

                $items = $items->whereIn('id', $collectors);
            }  else if ($type == 'narrators') {
                $narrators = Narrator::orderBy('fullname')
                                ->where('fullname', 'LIKE', '%'.$global.'%')
                                ->pluck('id');

                $items = $items->whereIn('id', $narrators);
            } else if ($type == 'places') {
                $places = Place::orderBy('name')
                                ->where('name', 'LIKE', '%'.$global.'%')
                                ->pluck('id');

                $items = $items->whereIn('id', $places);
            } else if ($type == 'sources') {
                $sources = Source::orderBy('identifier')
                                ->where('identifier', 'LIKE', '%'.$global.'%')
                                ->orWhere('title', 'LIKE', '%'.$global.'%')
                                ->orWhere('author', 'LIKE', '%'.$global.'%')
                                ->pluck('id');

                $items = $items->whereIn('id', $sources);
            }
            }
        }
        
        return $items;
    }

    /**
     * Highlights all instances of a fragment in a list of items.
     */
    public function highlight(mixed $items, string $type, string $fragment) {
        if (isset($items)) {
            if ($type == 'legends') {
                foreach ($items as $legend) {
                    $legend->identifier = $this->bold($legend->identifier, $fragment);
                    $legend->metadata = $this->bold($legend->metadata, $fragment);
                    $legend->title_lv = $this->bold($legend->title_lv, $fragment);
                    $legend->chapter_lv = $this->bold($legend->chapter_lv, $fragment);
                    $legend->volume = $this->bold($legend->volume, $fragment);

                    $legend->collector->fullname = $this->bold($legend->collector->fullname, $fragment);
                    $legend->narrator->fullname = $this->bold($legend->narrator->fullname, $fragment);
                    $legend->place->name = $this->bold($legend->place->name, $fragment);
                }
            } else if ($type == 'collectors' || $type == 'narrators') {
                foreach ($items as $person) {
                    $person->fullname = $this->bold($person->fullname, $fragment);
                }
            } else if ($type == 'places') {
                foreach ($items as $place) {
                    $place->name = $this->bold($place->name, $fragment);
                }
            } else if ($type == 'sources') {
                foreach ($items as $source) {
                    $source->identifier = $this->bold($source->identifier, $fragment);
                    $source->title = $this->bold($source->title, $fragment);
                    $source->author = $this->bold($source->author, $fragment);
                }
            }
        }
        
        return $items;
    }
    // kkāds prikols, kam jau padod pagination?
    // vai arī pagination veic iekš kopīgas search funkcijas - iespējams, tur varētu minēt tipus

    /**
     * Highlights any given instance of the given fragment in the given text.
     */
    public function bold(string $text, string $fragment)
    {
        $str_pos = mb_strpos($this->clean($text), $this->clean($fragment));
        if ($str_pos || mb_substr($this->clean($text), 0, mb_strlen($fragment)) == $this->clean($fragment)) {
            $text = mb_substr($text, 0, $str_pos)."<b>".mb_substr($text, $str_pos, mb_strlen($fragment))."</b>".mb_substr($text, $str_pos + mb_strlen($fragment));
        } else {
            $text = Str::limit($text, 100);
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
            foreach ($legends as $legend) {
                $text_lowercase = $this->clean($legend->text_lv);
                $str_pos = mb_strpos($text_lowercase, $text_frag);
                if (!$str_pos) { $legend->text = Str::limit($legend->text_lv, 100); continue; }
                $border_limit = intval((100 - mb_strlen($text_frag)) / 2);
                if(mb_strlen($legend->text_lv) > 100) {
                    if ($str_pos < $border_limit) {
                        $legend->text = mb_substr($legend->text_lv, 0, $str_pos)."<b>".mb_substr($legend->text_lv, $str_pos, mb_strlen($text_frag))."</b>".mb_substr($legend->text_lv, $str_pos + mb_strlen($text_frag), 100 - ($str_pos + mb_strlen($text_frag)))."...";
                    } else if ($str_pos > mb_strlen($legend->text_lv) - $border_limit) {
                        $legend->text = "...".mb_substr($legend->text_lv, mb_strlen($legend->text_lv) - 100, $str_pos - (mb_strlen($legend->text_lv) - 100))."<b>".mb_substr($legend->text_lv, $str_pos, mb_strlen($text_frag))."</b>".mb_substr($legend->text_lv, $str_pos + mb_strlen($text_frag));
                    } else {
                        $legend->text = "...".mb_substr($legend->text_lv, $str_pos - $border_limit, $border_limit)."<b>".mb_substr($legend->text_lv, $str_pos, mb_strlen($text_frag))."</b>".mb_substr($legend->text_lv, $str_pos + mb_strlen($text_frag), 100 - ($border_limit + mb_strlen($text_frag)))."...";
                    }
                } else {
                    $legend->text = mb_substr($legend->text_lv, 0, $str_pos)."<b>".mb_substr($legend->text_lv, $str_pos, mb_strlen($text_frag))."</b>".mb_substr($legend->text_lv, $str_pos + mb_strlen($text_frag));
                }
            }
        } else {
            foreach ($legends as $legend) {
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
        $fragmented['quoted'] = $quoted;
        $fragmented['unquoted'] = $unquoted;
        return $fragmented;
    }
}