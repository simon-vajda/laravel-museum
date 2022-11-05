<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory;

    public function comments()
    {
        return $this->hasMany(Comment::class)->orderBy('updated_at', 'desc');
    }

    public function labels()
    {
        return $this->belongsToMany(Label::class)->withTimestamps();
    }

    public function visibleLabels()
    {
        return $this->labels()->where('display', true);
    }
}
