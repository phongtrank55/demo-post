<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\StoryDetail;

class Story extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = ['id'];

    public function storyDetails()
    {
        return $this->hasMany(StoryDetail::class, 'story_id');
    }

}
