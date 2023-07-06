<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VideoStat extends Model
{
    use HasFactory;

    protected $table = '03cy_video_stats';

    public function video()
    {
        return $this->belongsTo(Video::class);
    }
}
