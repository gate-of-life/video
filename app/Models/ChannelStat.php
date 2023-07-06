<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChannelStat extends Model
{
    use HasFactory;

    protected $table = '03cy_channel_stats';

    public function channel()
    {
        return $this->belongsTo(Channel::class);
    }
}
