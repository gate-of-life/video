<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Channel;
use App\Models\Video;
use App\Models\VideoStat;
use Google\Service\YouTube;
use Google_Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class VideoController extends Controller
{
    /**
     * Show the form to choose channel for videos.
     */
    public function select_channel()
    {
        Gate::authorize('viewAny', Video::class);

        $channels = Auth::user()->channels;
        return inertia('User/Video/SelectChannel')->with([
            'channels' => $channels,
        ]);
    }

    /**
     * Get videos for the selected channel.
     */
    public function filter(Request $request)
    {
        Gate::authorize('viewAny', Video::class);

        $request->validate([
            'channel_id' => 'required|integer'
        ]);
        session()->put('channel_id', $request->input('channel_id'));

        return redirect(route('user.videos.index'));
    }

    /**
     * Get videos for the selected channel, for get request.
     */
    public function index()
    {
        Gate::authorize('viewAny', Video::class);

        $channel_id = session()->get('channel_id');

        $videos = Video::where('channel_id', $channel_id)->orderBy('published_at', 'DESC')->paginate(24);
        $channels = Auth::user()->channels;
        return inertia('User/Video/Index')->with([
            'videos' => $videos,
            'channels' => $channels,
            'channel_id' => $channel_id
        ]);
    }
}
