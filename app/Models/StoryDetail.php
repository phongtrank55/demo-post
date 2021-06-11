<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Goutte\Client;
use Symfony\Component\DomCrawler\Crawler;

class StoryDetail extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = ['id'];

    public function invoiceDetails()
    {
        return $this->belongsTo('App\Models\StoryDetail', 'story_id');
    }


}
