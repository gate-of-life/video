<?php

namespace App\Console\Commands;

use App\Models\Channel;
use App\Models\Video;
use App\Models\VideoStat;
use Carbon\Carbon;
use Google\Service\YouTube;
use Google_Client;
use Illuminate\Console\Command;

class FetchVideoData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fetch:video-data';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetches YouTube video data like comment count and the like.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $channels = Channel::all();
        $client = new Google_Client();
        $client->setDeveloperKey(env('YOUTUBE_API_KEY'));
        $service = new YouTube($client);
        $week_number = VideoStat::latest()->first()->week_number + 1;
        foreach ($channels as $key => $channel) {
            $params = [
                'id' => $channel->channel_id
            ];
            $next_page_token = null;
            $response = $service->channels->listChannels('contentDetails', $params)->items[0];
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
                $old_videos = Video::where('channel_id', $channel->id)->pluck('video_id')->toArray();
                $new_videos = array_diff($videos, $old_videos);
                if ($new_videos) {
                    $params = [
                        'id' => $new_videos,
                    ];
                    $responses_new = $service->videos->listVideos('snippet,statistics', $params)->items;
                    foreach ($responses_new as $res) {
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
                        $video_stat = new VideoStat();
                        $video_stat->video_id = $video->id;
                        $video_stat->week_number = $week_number;
                        $video_stat->comment_count = $res->statistics->commentCount ?? 0;
                        $video_stat->like_count = $res->statistics->likeCount ?? 0;
                        $video_stat->view_count = $res->statistics->viewCount ?? 0;
                        $video_stat->save();
                    }
                }
                $existing_videos = array_diff($videos, $new_videos);
                if ($existing_videos) {
                    $params = [
                        'id' => $existing_videos,
                    ];
                    $responses_existing = $service->videos->listVideos('snippet,statistics', $params)->items;
                    foreach ($responses_existing as $res) {
                        $video = Video::where(['channel_id' => $channel->id, 'video_id' => $res->id])->first();
                        $video->comment_count = $res->statistics->commentCount ?? 0;
                        $video->like_count = $res->statistics->likeCount ?? 0;
                        $video->view_count = $res->statistics->viewCount ?? 0;
                        $video->update();
                        $before_date = Carbon::now()->subYear();
                        $published_at = Carbon::parse($res->snippet->publishedAt);
                        if ($published_at->greaterThanOrEqualTo($before_date)) {
                            $video_stat = new VideoStat();
                            $video_stat->video_id = $video->id;
                            $video_stat->week_number = $week_number;
                            $video_stat->comment_count = $res->statistics->commentCount ?? 0;
                            $video_stat->like_count = $res->statistics->likeCount ?? 0;
                            $video_stat->view_count = $res->statistics->viewCount ?? 0;
                            if (VideoStat::where(['video_id' => $video->id, 'week_number' => $week_number - 1])->count() > 0) {
                                $video_stat->comment_change = ($res->statistics->commentCount ?? 0) - VideoStat::where(['video_id' => $video->id, 'week_number' => $week_number - 1])->first()->comment_count;
                                $video_stat->like_change = ($res->statistics->likeCount ?? 0) - VideoStat::where(['video_id' => $video->id, 'week_number' => $week_number - 1])->first()->like_count;
                                $video_stat->view_change = ($res->statistics->viewCount ?? 0) - VideoStat::where(['video_id' => $video->id, 'week_number' => $week_number - 1])->first()->view_count;
                            }
                            $video_stat->save();
                        }
                    }
                }
                $next_page_token = $playlist_items_response->getNextPageToken();
            } while ($next_page_token !== null);
        }
    }
}
