<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Narrator extends Model
{
    protected $fillable = [
        'fullname',
        'gender',
        'external_identifier',
    ];

    // Removes timestamps (default settings).
    public $timestamps = false;
    
    public function legends()
    {
        return $this->hasMany(Legend::class);
    }
}
