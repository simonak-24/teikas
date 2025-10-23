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
            Source::create([
                'identifier' => $line[1],
                'title' => $line[0],
                'author'=> null,
            ]);
            $sources[$line[1]] = $id;
            $id = $id + 1;
            $line = fgetcsv($seed_file);
        }
        fclose($seed_file);

        // Seeds the database using the original master table.
        $seed_file = fopen(base_path("database/data/master_table.csv"),"r");
        $line = fgetcsv($seed_file);
        $line = fgetcsv($seed_file);
        while ($line != NULL) {
            if (DB::table('collectors')->where('fullname', $line[6])->doesntExist()) {
                if ($line[6] != 'null') {
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
                    $collector_id = null;
                } else {
                    $collector_id = $collector->id;
                }
                $narrator = DB::table('narrators')->where('fullname', $line[8])->first();
                if (empty($narrator)) {
                    $narrator_id = null;
                } else {
                    $narrator_id = $narrator->id;
                }
                $place = DB::table('places')->where('name', $line[14])->first();
                if (empty($place)) {
                    $place_id = null;
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

        // Seeds the database by linking sources and legends. *ŠOBRĪD ŅEM TIKAI 1 KOLONNU AR AVOTIEM, BŪS JĀSALIEK ARĪ PĀRĒJIE!
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
