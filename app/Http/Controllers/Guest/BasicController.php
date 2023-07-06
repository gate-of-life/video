<?php

namespace App\Http\Controllers\Guest;

use App\Http\Controllers\Controller;
use App\Models\ChannelStat;
use App\Models\Video;
use App\Models\VideoStat;
use Illuminate\Http\Request;

class BasicController extends Controller
{
    public function index()
    {
        $week_number_video = VideoStat::latest()->first() ? VideoStat::latest()->first()->week_number : 0;
        $week_number_channel = ChannelStat::latest()->first() ? ChannelStat::latest()->first()->week_number : 0;
        $most_viewed_videos = VideoStat::where('week_number',  $week_number_video)->orderBy('view_change', 'DESC')->limit(3)->with('video.channel')->get();
        $most_liked_videos = VideoStat::where('week_number',  $week_number_video)->orderBy('like_change', 'DESC')->limit(3)->with('video.channel')->get();
        $most_commented_videos = VideoStat::where('week_number',  $week_number_video)->orderBy('comment_change', 'DESC')->limit(3)->with('video.channel')->get();
        $most_viewed_channels = ChannelStat::where('week_number',  $week_number_channel)->orderBy('view_change', 'DESC')->limit(3)->with('channel')->get();
        $trending_channels = ChannelStat::where('week_number',  $week_number_channel)->orderByRaw('subscriber_change / subscriber_count DESC')->limit(3)->with('channel')->get();
        return inertia('Guest/Welcome')->with([
            'most_viewed_videos' => $most_viewed_videos,
            'most_liked_videos' => $most_liked_videos,
            'most_commented_videos' => $most_commented_videos,
            'most_viewed_channels' => $most_viewed_channels,
            'trending_channels' => $trending_channels,
        ]);
    }
}
