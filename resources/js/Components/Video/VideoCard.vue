<script setup>
import moment from 'moment'
import formatCount from '@/Composables/formatCount'
defineProps({
    video: Object,
    video_stat: {
        type: Object,
        default: null
    }
})
</script>

<template>
    <div class="video-card">
        <a :href="`https://www.youtube.com/watch?v=${video.video_id}`">
            <div class="card me-lg-3">
                <div class="text-center thumbnail">
                    <img class="w-100" :src="video.thumbnail_medium" alt="">
                </div>
            </div>
            <div class='row'>
                <div class="col-2 text-end">
                    <img class="rounded-circle" :src="video.channel.thumbnail_medium" alt="">
                </div>
                <div class="col-10">
                    <h4 class="text-bold mb-0 mb-md-1">{{ video.title }}</h4>
                    <div class="d-inline statistics">
                        <small class="my-0">{{ video.channel.title }}</small>
                        <small class="text-bold mx-1 my-0">.</small>
                        <small class="my-0">{{ formatCount(video.view_count) }} views</small>
                        <small class="text-bold mx-1 my-0">.</small>
                        <small class="py-0">{{ moment(video.published_at).fromNow().replace('a month ago', '1 month ago')
                        }}</small>
                        <small class="text-bold mx-1 my-0">.</small>
                        <small class="my-0"><a class="my-0 text-decoration-underline"
                                :href="route('videos.show', video.id)"><b>stat</b></a></small>
                    </div>
                </div>
            </div>
        </a>
    </div>
</template>

<style scoped>
.video-card {
    padding-bottom: 2rem;
}

.video-card h2 {
    margin-bottom: 3rem;
}

.video-card h4 {
    font-size: 0.9rem;
    font-weight: 600;
}

a {
    text-decoration: none;
    color: #333333;
}

.video-card .card p {
    font-family: 'Roboto Condensed', sans-serif;
    font-size: 1rem;
}

hr {
    height: 0.5px;
    border: none;
    width: 20%;
}

.card:hover {
    box-shadow: 0 0 50px rgba(0, 50, 0, 0.2);
    transition: all 0.3s ease-in-out;
}

img {
    width: 100%;
}

@media (max-width: 991px) {

    /* For devices with a maximum width of 991px (tablet devices) */
    img {
        width: 70%;
    }
}

@media (max-width: 767px) {

    /* For devices with a maximum width of 767px (mobile devices) */
    img {
        width: 70%;
    }
}

.statistics p {
    font-size: 0.8rem;
}
</style>