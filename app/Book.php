<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    protected $fillable = ['title', 'release_date', 'author_id'];

    public function author()
    {
        return $this->belongsTo('App\Author', 'author_id');
    }
}
