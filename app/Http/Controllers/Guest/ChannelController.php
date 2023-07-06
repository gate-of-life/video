<?php

namespace App\Http\Controllers\Guest;

use App\Http\Controllers\Controller;
use App\Models\Channel;
use App\Models\ChannelStat;
use App\Models\Video;
use Google\Service\YouTube;
use Google_Client;
use Illuminate\Http\Request;

class ChannelController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $channels = Channel::all();
        $week_number = ChannelStat::latest()->first() ? ChannelStat::latest()->first()->week_number : 0;
        $most_subscriber = ChannelStat::where('week_number',  $week_number)->orderBy('subscriber_change', 'DESC')->with('channel')->first();
        $most_video = ChannelStat::where('week_number',  $week_number)->orderBy('video_change', 'DESC')->with('channel')->first();
        $most_viewed = ChannelStat::where('week_number',  $week_number)->orderBy('view_change', 'DESC')->with('channel')->first();
        $trending = ChannelStat::where('week_number',  $week_number)->orderByRaw('subscriber_change / subscriber_count DESC')->with('channel')->first();
        return inertia('Guest/Channel/Index')->with([
            'channels' => $channels,
            'most_subscriber' => $most_subscriber,
            'most_video' => $most_video,
            'most_viewed' => $most_viewed,
            'trending' => $trending,
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $channel = Channel::with(['channel_categories', 'channel_stats'])->findOrFail($id);
        $videos = Video::where('channel_id', $id)->with('channel')->orderBy('published_at', 'DESC')->paginate(24);
        return inertia('Guest/Channel/Show')->with([
            'channel' => $channel,
            'videos' => $videos,
        ]);
    }
}
