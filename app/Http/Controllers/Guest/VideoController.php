<?php

namespace App\Http\Controllers\Guest;

use App\Http\Controllers\Controller;
use App\Models\Video;
use App\Models\VideoStat;
use Illuminate\Http\Request;

class VideoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $videos = Video::with(['channel', 'video_stats'])->orderBy('published_at', 'DESC')->paginate(24);
        $week_number = VideoStat::latest()->first() ? VideoStat::latest()->first()->week_number : 0;
        $most_commented = VideoStat::where('week_number',  $week_number)->orderBy('comment_change', 'DESC')->with('video.channel')->first();
        $most_liked = VideoStat::where('week_number',  $week_number)->orderBy('like_change', 'DESC')->with('video.channel')->first();
        $most_viewed = VideoStat::where('week_number',  $week_number)->orderBy('view_change', 'DESC')->with('video.channel')->first();
        return inertia('Guest/Video/Index')->with([
            'videos' => $videos,
            'most_commented' => $most_commented,
            'most_liked' => $most_liked,
            'most_viewed' => $most_viewed
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $video = Video::with('video_stats')->findOrFail($id);
        return inertia('Guest/Video/Show')->with([
            'video' => $video,
        ]);
    }
}
