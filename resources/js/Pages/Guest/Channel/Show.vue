<script setup>
import { onMounted, onUnmounted, ref } from 'vue'
import upArrow from '@/../../public/image/up-arrow.png'
import useScroller from '@/Composables/useScroller'
import 'moment'
import 'chartjs-adapter-moment'
import Chart from 'chart.js/auto'
import VideoCard from '@/Components/Video/VideoCard.vue'
import Loader from '@/Components/Loader.vue'
import { router } from '@inertiajs/vue3'
import formatCount from '@/Composables/formatCount'
const props = defineProps({
    channel: Object,
    videos: Object
})
const allVideos = ref([]);
let isLoading = ref(false)
let isAllPagesLoaded = ref(false)
const activeTab = ref('pills-videos-tab');
const loadVideos = (url = route('channels.show', props.channel.id)) => {
    if (isAllPagesLoaded.value) {
        return
    }
    isLoading.value = true;
    router.visit(url, {
        method: 'get',
        preserveState: true,
        preserveScroll: true,
        only: ['videos'],
        onSuccess: page => {
            allVideos.value = [...allVideos.value, ...page.props.videos.data]
            window.history.replaceState({}, page.props.url, `/channels/${props.channel.id}`)
            if (!page.props.videos.next_page_url) {
                isAllPagesLoaded.value = true
            }
            isLoading.value = false
        },
    })
}
const handleScroll = () => {
    if (activeTab.value === 'pills-videos-tab') {
        const scrollHeight = document.documentElement.scrollHeight;
        const scrollTop = document.documentElement.scrollTop || document.body.scrollTop;
        const clientHeight = document.documentElement.clientHeight;

        if (scrollHeight - scrollTop - clientHeight < 100 && !isLoading.value) {
            loadVideos(props.videos.next_page_url)
        }
    }
}
const subscriber_trend_data = props.channel.channel_stats.map((stat) => {
    return ({
        'x': stat.created_at,
        'y': stat.subscriber_count
    })
})
const video_trend_data = props.channel.channel_stats.map((stat) => {
    return ({
        'x': stat.created_at,
        'y': stat.video_count
    })
})
const view_trend_data = props.channel.channel_stats.map((stat) => {
    return ({
        'x': stat.created_at,
        'y': stat.view_count
    })
})
const plotChart = (data, label, context) => {
    new Chart(context, {
        type: 'line',
        data: {
            datasets: [{
                data: data,
                label: label,
                backgroundColor: '#1e90ff',
                borderColor: '#1e90ff',
                fill: false
            }]
        },
        options: {
            scales: {
                y: {
                    title: {
                        display: true,
                        text: label
                    }
                },
                x: {
                    type: 'time',
                    time: {
                        unit: 'day'
                    }
                }
            }
        }
    })
}
onMounted(() => {
    loadVideos()
    window.addEventListener('scroll', handleScroll)
    useScroller()
    const subscriberContext = document.getElementById('subscribers_trendline').getContext('2d');
    const videoContext = document.getElementById('videos_trendline').getContext('2d');
    const viewContext = document.getElementById('views_trendline').getContext('2d');
    plotChart(subscriber_trend_data, 'Subscribers', subscriberContext)
    plotChart(video_trend_data, 'Videos', videoContext)
    plotChart(view_trend_data, 'Views', viewContext)
    const tabButtons = document.querySelectorAll('[data-bs-toggle="pill"]');
    tabButtons.forEach((button) => {
        button.addEventListener('click', () => {
            activeTab.value = button.id
        })
    })
})
onUnmounted(() => {
    window.removeEventListener('scroll', handleScroll);
})
</script>
<script>
import GuestLayout from '@/Layouts/GuestLayout.vue';
export default {
    layout: GuestLayout
}
</script>

<template>
    <Head title="Channel Details" />

    <div class="row py-5 mt-5 bg-light-gray">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-4 text-center">
                    <img class="mt-4 p-md-3 rounded-circle" :src="channel.thumbnail_medium" alt="">
                </div>
                <div class="col-md-8 px-4 my-4 my-md-2">
                    <h4 class="d-none d-md-block">{{ channel.title }}</h4>
                    <div class="statistics">
                        <small class="my-0">{{ channel.custom_url }}</small>
                        <small class="text-bold mx-1 my-0">.</small>
                        <small class="my-0">{{ formatCount(channel.subscriber_count) }} subscribers</small>
                        <small class="text-bold mx-1 my-0">.</small>
                        <small class="py-0">{{ formatCount(channel.video_count) }} videos</small>
                    </div>
                    <div class="mt-2">{{ channel.description.length > 250 ? channel.description.slice(0, 250) + '...' :
                        channel.description
                    }}</div>
                </div>
            </div>
        </div>
    </div>
    <hr class="mb-1">
    <div class="container">
        <div class="text-end mb-3 p-0">
            <Link class="text-right" :href="route('channels.index')">Back to channels list</Link>
        </div>
        <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active text-uppercase" id="pills-videos-tab" data-bs-toggle="pill"
                    data-bs-target="#pills-videos" type="button" role="tab" aria-controls="pills-videos"
                    aria-selected="true">Vidoes</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link text-uppercase" id="pills-statistics-tab" data-bs-toggle="pill"
                    data-bs-target="#pills-statistics" type="button" role="tab" aria-controls="pills-statistics"
                    aria-selected="false">Statistics</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link text-uppercase" id="pills-about-tab" data-bs-toggle="pill"
                    data-bs-target="#pills-about" type="button" role="tab" aria-controls="pills-about"
                    aria-selected="false">About</button>
            </li>
        </ul>
        <div class="tab-content" id="pills-tabContent">
            <div class="tab-pane fade show active" id="pills-videos" role="tabpanel" aria-labelledby="pills-videos-tab">
                <div class="py-3">
                    <hr class="mt-0">
                    <div class="row">
                        <template v-if="allVideos">
                            <div class="col-lg-4 col-xl-3" v-for="video in allVideos" :key="video.id">
                                <VideoCard :video="video" />
                            </div>
                        </template>
                    </div>
                </div>
            </div>
            <div class="tab-pane fade" id="pills-statistics" role="tabpanel" aria-labelledby="pills-statistics-tab">
                <div class="py-3">
                    <hr class="mt-0">
                    <div class="mb-5">
                        <h4 class="my-4 text-uppercase">Subscriber trend</h4>
                        <canvas id="subscribers_trendline"></canvas>
                    </div>
                    <hr>
                    <div class="mb-5">
                        <h4 class="my-4 text-uppercase">Video trend</h4>
                        <canvas id="videos_trendline"></canvas>
                    </div>
                    <hr>
                    <div class="mb-5">
                        <h4 class="my-4 text-uppercase">View trend</h4>
                        <canvas id="views_trendline"></canvas>
                    </div>
                </div>
            </div>
            <div class="tab-pane fade" id="pills-about" role="tabpanel" aria-labelledby="pills-about-tab">
                <div class="py-3">
                    <ul class="list-group list-group-unbordered">
                        <li class="list-group-item">
                            <b>Description</b>
                            <div class="float-right">{{ channel.description }}</div>
                        </li>
                        <li class="list-group-item">
                            <b>Channel categories</b>
                            <template v-for="(name, index) in channel.channel_categories.map((category) => category.name)"
                                :key="index">
                                <a class="float-right mb-1 bg-light-gray mx-2 p-1 rounded">{{ name }}</a>
                            </template>
                        </li>
                        <li class="list-group-item">
                            <b>Date published</b> <a class="float-right">{{ new
                                Date(channel.published_at).toLocaleString('en-us',
                                    {
                                        month: 'long', day: 'numeric', year: 'numeric'
                                    }) }}</a>
                        </li>
                        <li class="list-group-item">
                            <b>Country</b> <a class="float-right">{{ channel.country }}</a>
                        </li>
                        <li class="list-group-item">
                            <b>Made for kids</b> <a class="float-right">{{ channel.made_for_kids }}</a>
                        </li>
                        <li class="list-group-item">
                            <b>Privacy status</b> <a class="float-right">{{ channel.privacy_status }}</a>
                        </li>
                        <li class="list-group-item">
                            <b>Keywords</b> <a class="float-right">{{ channel.keywords }}</a>
                        </li>
                        <li class="list-group-item">
                            <b>Topic categories</b> <a class="float-right">{{ channel.topic_categories }}</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <Loader v-if="isLoading" />
    <button id="myBtn">
        <img :src="upArrow" alt="alternative" />
    </button>
</template>

<style scoped>
h2 {
    font-family: 'Roboto Condensed', sans-serif;
    font-size: 3rem;
}

h3 {
    font-family: 'Roboto Condensed', sans-serif;
    font-size: 2rem;
}

h4 {
    font-family: 'Roboto Condensed', sans-serif;
    font-weight: 600;
    font-size: 1.3rem;
}

p {
    font-family: 'Roboto Condensed', sans-serif;
    font-size: 1.2rem;
    font-weight: 300;
}

.statistics small {
    font-size: 0.9rem;
}

#myBtn {
    position: fixed;
    z-index: 99;
    bottom: 20px;
    right: 20px;
    display: none;
    width: 40px;
    height: 40px;
    border: none;
    border-radius: 50%;
    outline: none;
    background-color: #777777;
    cursor: pointer;
}

#myBtn:hover {
    background-color: #444444;
}

#myBtn img {
    margin-bottom: 0.25rem;
    width: 15px;
}

.bg-light-gray {
    background-color: #f0f0f0;
}

img {
    width: 60%;
}

hr {
    border: none;
    height: 1px;
    background-color: rgba(0, 0, 0, 1);
}
</style>
