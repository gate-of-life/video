<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Video extends Model
{
    use HasFactory;

    protected $table = '03cy_videos';

    public function channel()
    {
        return $this->belongsTo(Channel::class);
    }

    public function video_stats()
    {
        return $this->hasMany(VideoStat::class);
    }
}
