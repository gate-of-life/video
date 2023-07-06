<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Channel extends Model
{
    use HasFactory;

    protected $table = '03cy_channels';

    protected $appends = ['is_linked', 'made_for_kids'];

    public function getIsLinkedAttribute()
    {
        return $this->attributes['is_linked'] == 1 ? 'Yes' : 'No';
    }

    public function getMadeForKidsAttribute()
    {
        return $this->attributes['made_for_kids'] == 1 ? 'Yes' : 'No';
    }

    public function channel_categories()
    {
        return $this->belongsToMany(ChannelCategory::class, '03cy_channel_channel_category');
    }

    public function videos()
    {
        return $this->hasMany(Video::class);
    }

    public function channel_stats()
    {
        return $this->hasMany(ChannelStat::class);
    }
}
