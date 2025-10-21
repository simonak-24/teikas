<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Source extends Model
{
    protected $fillable = [
        'identifier',
        'title',
        'author',
    ];
    
    // Removes timestamps (default settings).
    public $timestamps = false;
    
    public function legends()
    {
        return $this->hasMany(LegendSource::class);
    }
}
