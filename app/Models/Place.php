<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Place extends Model
{
    protected $fillable = [
        'name',
        'latitude',
        'longitude',
        'external_identifier',
    ];

    // Removes timestamps (default settings).
    public $timestamps = false;
    
    public function legends()
    {
        return $this->hasMany(Legend::class);
    }
}
