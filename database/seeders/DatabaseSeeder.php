<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Collector;
use App\Models\Narrator;
use App\Models\Place;
use App\Models\Source;
use App\Models\Legend;
use App\Models\LegendSource;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Test User',
            // 'email' => 'test@example.com',
        ]);

        // Seeds the database using the source table.
        $sources = array();
        $id = 1;
        $seed_file = fopen(base_path("database/data/source_table.csv"),"r");
        $line = fgetcsv($seed_file);
        $line = fgetcsv($seed_file);
        while ($line != NULL) {
            $temp_title = $line[0];
            $temp_author = "";

            // Finds and separates authors from titles (which are mostly in quotes, exceptions are taken into account).
            $quote_pos = mb_strpos($line[0], '"');
            if (!($quote_pos === false)) {
                if ($quote_pos != 0) {
                    $temp_author = "";
                    $temp_author = mb_substr($line[0], 0, $quote_pos-1);
                    $temp_title = "";
                    $temp_title = mb_substr($line[0], $quote_pos+1, mb_strlen($line[0])-mb_strlen($temp_author)-2);
                    $temp_title = trim($temp_title, "\"");
                }
            }
            if ($temp_author == "Strenču mēnešraksts" || $temp_author == "Žurnāls" || $temp_author == "Jelgavas Latviešu biedrības Rakstniecības nodaļas rakstu krājums") {
                $temp_title = $line[0];
                $temp_author = "";
            }
            if ($temp_author == "Lerhis-Puškaitis, A." && mb_strpos($temp_title, '7')) {
                $temp_title = mb_substr($line[0], $quote_pos);
            }
            if ($temp_author == "Aizsils Arvīds") {
                $temp_title = mb_substr($line[0], $quote_pos+1, 22);
            }

            // Finds and copies authors from collections (indicated by the usage of "krājums").
            $collection_pos = mb_strpos($line[0], 'krājums');
            $biedriba_pos = mb_strpos($line[0], 'biedrības');
            $kulturas_pos = mb_strpos($line[0], 'ultūra');
            if (!($collection_pos === false) && $biedriba_pos === false && $kulturas_pos === false) {
                $temp = trim($line[0]);
                $temp_names = explode(" ", trim(mb_substr($temp, 0, mb_strlen($temp) - mb_strlen(" krājums"), "utf-8")));
                if ($temp_names[0] == "Bērzkalnes") {
                    $temp_author = "Bērzkalne Anna";
                } else if ($temp_names[0] == "Brīvzemnieks") {
                    $temp_author = "Brīvzemnieks Fricis";
                } else if ($temp_names[0] == "Tabines") {
                    $temp_author = "Tabine Rozālija";
                } else {
                    $temp_author = "";
                    foreach ($temp_names as $name) {
                        if ($name != "") {
                            if (!(strpos($name, 'Mārtiņa') === false) || !(strpos($name, 'Celmiņa') === false)|| !(strpos($name, 'Kundziņa') === false)) {
                                $temp_author = $temp_author.mb_substr($name, 0, mb_strlen($name)-1, "utf-8")."š ";
                            } else if (!(strpos($name, 'ļa') === false)) {
                                $temp_author = $temp_author.mb_substr($name, 0, mb_strlen($name)-2, "utf-8")."lis ";
                            } else if (!(strpos($name, 'ņa') === false)) {
                                $temp_author = $temp_author.mb_substr($name, 0, mb_strlen($name)-2, "utf-8")."nis ";
                            } else if (!(strpos($name, '.') === false)) {
                                $temp_author = $temp_author.$name." ";
                            } else {
                                $temp_author = $temp_author.mb_substr($name, 0, mb_strlen($name)-1, "utf-8")."s ";
                            }
                        }
                    }
                    $temp_author = mb_substr($temp_author, 0, mb_strlen($temp_author)-1, "utf-8");
                    // add "Pēters" => "Pēteris"
                }
            }

            Source::create([
                'identifier' => $line[1],
                'title' => $temp_title,
                'author'=> $temp_author,
            ]);
            $sources[$line[1]] = $id;
            $id = $id + 1;
            $line = fgetcsv($seed_file);
        }
        fclose($seed_file);

        // Adds unknown collector / narrator / place.
        Collector::create([
            'fullname' => 'Nezināms',
            'gender' => null,
        ]);
        Narrator::create([
            'fullname' => 'Nezināms',
            'gender' => null,
        ]);
        Place::create([
            'name' => 'Nezināma',
            'latitude' => (double)0,
            'longitude' => (double)0,
        ]);

        // Seeds the database using the original master table.
        $seed_file = fopen(base_path("database/data/master_table.csv"),"r");
        $line = fgetcsv($seed_file);
        $line = fgetcsv($seed_file);
        while ($line != NULL) {
            if (DB::table('collectors')->where('fullname', $line[6])->doesntExist()) {
                if ($line[6] != 'null' && $line[6] != 'Nezināms') {
                    $gender = $line[7];
                    if ($gender == 'null') {
                        $gender = null;
                    }
                    Collector::create([
                        'fullname' => $line[6],
                        'gender' => $gender,
                    ]);
                }
            }
            if (DB::table('narrators')->where('fullname', $line[8])->doesntExist()) {
                if ($line[8] != 'null') {
                    $gender = $line[9];
                    if ($gender == 'null') {
                        $gender = null;
                    }
                    Narrator::create([
                        'fullname' => $line[8],
                        'gender' => $gender,
                    ]);
                }
            }
            if (DB::table('places')->where('name', $line[14])->doesntExist()) {
                if ($line[14] != 'null') {
                    Place::create([
                        'name' => $line[14],
                        'latitude' => (double)$line[15] ?? null,
                        'longitude' => (double)$line[16] ?? null,
                    ]);
                }
            }

            if (DB::table('legends')->where('identifier', $line[0])->doesntExist()) {
                $collector = DB::table('collectors')->where('fullname', $line[6])->first();
                if (empty($collector)) {
                    $collector_id = 1;
                } else {
                    $collector_id = $collector->id;
                }
                $narrator = DB::table('narrators')->where('fullname', $line[8])->first();
                if (empty($narrator)) {
                    $narrator_id = 1;
                } else {
                    $narrator_id = $narrator->id;
                }
                $place = DB::table('places')->where('name', $line[14])->first();
                if (empty($place)) {
                    $place_id = 1;
                } else {
                    $place_id = $place->id;
                }

                Legend::create([
                    'identifier' => $line[0],
                    'metadata' => $line[12],
                    'title_lv' => $line[4],
                    'title_de' => $line[5],
                    'text_lv'  => $line[18],
                    'text_de'  => $line[19],
                    'chapter_lv' => $line[2],
                    'chapter_de' => $line[3],
                    'volume' => $line[1],
                    'comments' => $line[13],
                    'collector_id' => $collector_id,
                    'narrator_id' => $narrator_id,
                    'place_id' => $place_id,
                ]);
            }

            $line = fgetcsv($seed_file);
        }
        fclose($seed_file);

        // Seeds the database by linking sources and legends.
        $seed_file = fopen(base_path("database/data/link_table.csv"),"r");
        $line = fgetcsv($seed_file);
        $line = fgetcsv($seed_file);
        while ($line != NULL) {
            $legends = DB::table('legends')->where('metadata', 'LIKE', '%'.$line[0].'%')->get();
            for ($i = 2; $i < count($line); $i = $i + 3) {
                if ($line[$i] != '') {
                    foreach ($legends as $legend) {
                        $legend_sources = DB::table('legend_sources')->where('legend_id', $legend->id)->get();
                        $link_exists = false;
                        foreach ($legend_sources as $legend_source) {
                            if ($legend_source->source_id == $sources[$line[$i]]) {
                                $link_exists = true;
                                break;
                            }
                        }
                        if (!$link_exists) {
                            LegendSource::create([
                                'legend_id' => $legend->id,
                                'source_id' => $sources[$line[$i]],
                            ]);
                        }
                    }
                }
            }
            $line = fgetcsv($seed_file);
        }
        fclose($seed_file);
    }
}
