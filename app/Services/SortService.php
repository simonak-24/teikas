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
            $legends = $legends = Legend::orderBy('identifier');
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
        
        $text_frag = mb_strtolower($request->text);
        $replacements = array("ā"=>"a", "č"=>"c", "ē"=>"e", "ģ"=>"g", "ī"=>"i", "ķ"=>"k", "ļ"=>"l", "ņ"=>"n", "ŗ"=>"r", "š"=>"s", "ū"=>"u", "ž"=>"z");
        foreach ($replacements as $search => $replace) { $text_frag = mb_eregi_replace($search, $replace, $text_frag); }
        
        if ($request->text != '') {
            $legends = $legends->where('text_lv', 'LIKE', '%'.$text_frag.'%');
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
     * Highlights and shortens legend texts according to the given text fragment.
     */
    public function text(mixed $legends, string $text)
    {
        $text_frag = mb_strtolower($text);
        $replacements = array("ā"=>"a", "č"=>"c", "ē"=>"e", "ģ"=>"g", "ī"=>"i", "ķ"=>"k", "ļ"=>"l", "ņ"=>"n", "ŗ"=>"r", "š"=>"s", "ū"=>"u", "ž"=>"z");
        foreach ($replacements as $search => $replace) { $text_frag = mb_eregi_replace($search, $replace, $text_frag); }

        if ($text != '') {
            foreach ($legends as $legend) {
                $text_lowercase = mb_strtolower($legend->text_lv);
                foreach ($replacements as $search => $replace) { $text_lowercase = mb_eregi_replace($search, $replace, $text_lowercase); }
                $str_pos = mb_strpos($text_lowercase, $text_frag);
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
}