<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LegendSource extends Model
{
    // Removes timestamps (default settings).
    public $timestamps = false;
    
    public function legend()
    {
        return $this->belongsTo(Legend::class);
    }
    public function source()
    {
        return $this->source(Source::class);
    }
}
