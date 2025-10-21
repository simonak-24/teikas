<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Legend extends Model
{
    protected $fillable = [
        'identifier',
        'metadata',
        'title_lv',
        'title_de',
        'text_lv',
        'text_de',
        'chapter_lv',
        'chapter_de',
        'volume',
        'comments',
    ];

    // Removes timestamps (default settings).
    public $timestamps = false;
    
    public function collector()
    {
        return $this->belongsTo(Collector::class);
    }
    public function narrator()
    {
        return $this->belongsTo(Narrator::class);
    }
    public function place()
    {
        return $this->belongsTo(Place::class);
    }
    public function sources()
    {
        return $this->hasMany(LegendSource::class);
    }
}
