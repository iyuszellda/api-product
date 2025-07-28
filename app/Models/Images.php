<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Images extends Model
{
    protected $table = 'source_images';
    public $timestamps = false;
    protected $fillable = ['product_id', 'sequence', 'title', 'url'];
}
