<?php

namespace App\Console\Commands;

use App\Models\Channel;
use App\Models\ChannelStat;
use Google\Service\YouTube;
use Google_Client;
use Illuminate\Console\Command;

class FetchChannelData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fetch:channel-data';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetches YouTube channel data like subscriber count and the like.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $channels = Channel::all();
        $client = new Google_Client();
        $client->setDeveloperKey(env('YOUTUBE_API_KEY'));
        $service = new YouTube($client);
        if (ChannelStat::latest()->count() > 0) {
            $week_number = ChannelStat::latest()->first()->week_number + 1;
        } else {
            $week_number = 1;
        }
        foreach ($channels as $key => $channel) {
            $queryParams = [
                'id' => $channel->channel_id
            ];
            $response = $service->channels->listChannels('snippet,statistics,status,topicDetails,localizations,brandingSettings', $queryParams)->items[0];
            $channel->thumbnail_default = $response->snippet->thumbnails->default->url;
            $channel->thumbnail_medium = $response->snippet->thumbnails->medium->url;
            $channel->thumbnail_high = $response->snippet->thumbnails->high->url;
            $channel->subscriber_count = $response->statistics->subscriberCount ?? 0;
            $channel->video_count = $response->statistics->videoCount ?? 0;
            $channel->view_count = $response->statistics->viewCount ?? 0;
            $channel->update();
            $channel_stat = new ChannelStat();
            $channel_stat->channel_id = $channel->id;
            $channel_stat->week_number = $week_number;
            $channel_stat->subscriber_count = $response->statistics->subscriberCount ?? 0;
            $channel_stat->video_count = $response->statistics->videoCount ?? 0;
            $channel_stat->view_count = $response->statistics->viewCount ?? 0;
            if (ChannelStat::where(['channel_id' => $channel->id, 'week_number' => $week_number - 1])->count() > 0) {
                $channel_stat->subscriber_change = ($response->statistics->subscriberCount ?? 0) - ChannelStat::where(['channel_id' => $channel->id, 'week_number' => $week_number - 1])->first()->subscriber_count;
                $channel_stat->video_change = ($response->statistics->videoCount ?? 0) - ChannelStat::where(['channel_id' => $channel->id, 'week_number' => $week_number - 1])->first()->video_count;
                $channel_stat->view_change = ($response->statistics->viewCount ?? 0) - ChannelStat::where(['channel_id' => $channel->id, 'week_number' => $week_number - 1])->first()->view_count;
            }
            $channel_stat->save();
        }
    }
}
