<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Channel;
use App\Models\ChannelCategory;
use App\Models\Video;
use App\Models\VideoStat;
use Google\Service\YouTube;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Google_Client;

class ChannelController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        Gate::authorize('viewAny', Channel::class);

        $channels = Auth::user()->channels;
        $channel_categories = ChannelCategory::all();
        return inertia('User/Channel/Index')->with([
            'channels' => $channels,
            'channel_categories' => $channel_categories,
            'can' => [
                'create_channel' => Auth::user()->can('create', Channel::class),
            ],
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        Gate::authorize('create', Channel::class);

        $request->validate([
            'channel_id' => 'string|required|unique:03cy_channels',
            'channel_category_id.*' => 'integer|required',
        ]);
        $client = new Google_Client();
        $client->setDeveloperKey(env('YOUTUBE_API_KEY'));
        $service = new YouTube($client);
        $queryParams = [
            'id' => $request->channel_id
        ];
        $response = $service->channels->listChannels('snippet,statistics,status,topicDetails,brandingSettings,contentDetails', $queryParams)->items[0];
        $channel = new Channel();
        $channel->user_id = Auth::id();
        $channel->etag = $response->etag;
        $channel->channel_id = $response->id;
        $channel->title = $response->snippet->title;
        $channel->description = $response->snippet->description;
        $channel->custom_url = $response->snippet->customUrl;
        $channel->published_at = $response->snippet->publishedAt;
        $channel->thumbnail_default = $response->snippet->thumbnails->default->url;
        $channel->thumbnail_medium = $response->snippet->thumbnails->medium->url;
        $channel->thumbnail_high = $response->snippet->thumbnails->high->url;
        $channel->country = $response->snippet->country;
        $channel->keywords = $response->brandingSettings->channel->keywords;
        $channel->unsubscribed_trailer = $response->brandingSettings->channel->unsubscribedTrailer;
        $channel->is_linked = $response->status->isLinked;
        $channel->long_uploads_status = $response->status->longUploadsStatus;
        $channel->made_for_kids = $response->status->madeForKids;
        $channel->privacy_status = $response->status->privacyStatus;
        $channel->topic_categories = $response->topicDetails ? implode(', ', $response->topicDetails->topicCategories) : '';
        $channel->subscriber_count = $response->statistics->subscriberCount;
        $channel->video_count = $response->statistics->videoCount;
        $channel->view_count = $response->statistics->viewCount;
        $channel->save();
        $channel->channel_categories()->attach($request->channel_category_id);
        $next_page_token = null;
        $uploads_playlist_id = $response->contentDetails->relatedPlaylists->uploads;
        do {
            $playlist_items_response = $service->playlistItems->listPlaylistItems('snippet', array(
                'playlistId' => $uploads_playlist_id,
                'maxResults' => 50,
                'pageToken' => $next_page_token,
            ));
            $videos = [];
            foreach ($playlist_items_response->items as $item) {
                $videos[] = $item->snippet->resourceId->videoId;
            }
            $params = [
                'id' => $videos,
            ];
            $responses = $service->videos->listVideos('snippet,statistics', $params)->items;
            foreach ($responses as $res) {
                $existing_video = Video::where('video_id', $res->id)->first();
                if (!$existing_video) {
                    $video = new Video();
                    $video->channel_id = $channel->id;
                    $video->etag = $res->etag;
                    $video->video_id = $res->id;
                    $video->catogory_id = $res->snippet->categoryId;
                    $video->default_audio_language = $res->snippet->defaultAudioLanguage;
                    $video->default_language = $res->snippet->defaultLanguage;
                    $video->description = $res->snippet->description;
                    $video->live_broadcast_content = $res->snippet->liveBroadcastContent;
                    $video->published_at = $res->snippet->publishedAt;
                    if (is_array($res->snippet->tags)) {
                        $video->tags = implode(', ', $res->snippet->tags);
                    } else {
                        $video->tags = $res->snippet->tags;
                    }
                    $video->title = $res->snippet->title;
                    $video->thumbnail_default = $res->snippet->thumbnails->default->url;
                    $video->thumbnail_medium = $res->snippet->thumbnails->medium->url;
                    $video->thumbnail_high = $res->snippet->thumbnails->high->url;
                    if ($res->snippet->thumbnails->standard) {
                        $video->thumbnail_standard = $res->snippet->thumbnails->standard->url;
                    }
                    if ($res->snippet->thumbnails->maxres) {
                        $video->thumbnail_maxres = $res->snippet->thumbnails->maxres->url;
                    }
                    $video->comment_count = $res->statistics->commentCount ?? 0;
                    $video->like_count = $res->statistics->likeCount ?? 0;
                    $video->view_count = $res->statistics->viewCount ?? 0;
                    $video->save();
                }
            }
            $next_page_token = $playlist_items_response->getNextPageToken();
        } while ($next_page_token !== null);
        return redirect(route('user.channels.index'))->with('success', 'Channel added successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $channel = Channel::with('channel_categories')->findOrFail($id);
        Gate::authorize('view', $channel);
        $channel_categories = ChannelCategory::all();
        return inertia('User/Channel/Show')->with([
            'channel' => $channel,
            'channel_categories' => $channel_categories,
            'can' => [
                'edit_channel' => Auth::user()->can('update', $channel),
                'delete_channel' => Auth::user()->can('delete', $channel),
            ],
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $channel = Channel::findOrFail($id);
        Gate::authorize('update', $channel);

        $client = new Google_Client();
        $client->setDeveloperKey(env('YOUTUBE_API_KEY'));
        $service = new YouTube($client);
        $queryParams = [
            'id' => $channel->channel_id
        ];
        $response = $service->channels->listChannels('snippet,statistics,status,topicDetails,brandingSettings', $queryParams)->items[0];
        $channel->etag = $response->etag;
        $channel->title = $response->snippet->title;
        $channel->description = $response->snippet->description;
        $channel->custom_url = $response->snippet->customUrl;
        $channel->published_at = $response->snippet->publishedAt;
        $channel->thumbnail_default = $response->snippet->thumbnails->default->url;
        $channel->thumbnail_medium = $response->snippet->thumbnails->medium->url;
        $channel->thumbnail_high = $response->snippet->thumbnails->high->url;
        $channel->country = $response->snippet->country;
        $channel->keywords = $response->brandingSettings->channel->keywords;
        $channel->unsubscribed_trailer = $response->brandingSettings->channel->unsubscribedTrailer;
        $channel->is_linked = $response->status->isLinked;
        $channel->long_uploads_status = $response->status->longUploadsStatus;
        $channel->made_for_kids = $response->status->madeForKids;
        $channel->privacy_status = $response->status->privacyStatus;
        $channel->topic_categories = implode(', ', $response->topicDetails->topicCategories);
        $channel->subscriber_count = $response->statistics->subscriberCount;
        $channel->video_count = $response->statistics->videoCount;
        $channel->view_count = $response->statistics->viewCount;
        $channel->save();
        return back()->with('success', 'Channel updated successfully.');
    }

    /**
     * Change channel category.
     */
    public function change_category(Request $request, string $id)
    {
        $channel = Channel::findOrFail($id);
        Gate::authorize('update', $channel);

        $request->validate([
            'channel_category_id.*' => 'integer|required',
        ]);
        $channel->channel_categories()->detach();
        $channel->channel_categories()->attach($request->channel_category_id);
        return back()->with('success', 'Channel categories updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $channel = Channel::findOrFail($id);
        Gate::authorize('delete', $channel);

        $channel->channel_categories()->detach();
        $channel->delete();
        return redirect(route('user.channels.index'))->with('success', 'Channel deleted successfully');
    }
}
