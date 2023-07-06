<script setup>
import { onMounted } from 'vue'
import upArrow from '@/../../public/image/up-arrow.png'
import useScroller from '@/Composables/useScroller'
import 'moment'
import 'chartjs-adapter-moment'
import Chart from 'chart.js/auto'
const props = defineProps({
    video: Object
})
const comment_trend_data = props.video.video_stats.map((stat) => {
    return ({
        'x': stat.created_at,
        'y': stat.comment_count
    })
})
const like_trend_data = props.video.video_stats.map((stat) => {
    return ({
        'x': stat.created_at,
        'y': stat.like_count
    })
})
const view_trend_data = props.video.video_stats.map((stat) => {
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
    useScroller()
    const commentContext = document.getElementById('comments_trendline').getContext('2d');
    const likeContext = document.getElementById('likes_trendline').getContext('2d');
    const viewContext = document.getElementById('views_trendline').getContext('2d');
    plotChart(comment_trend_data, 'Comments', commentContext)
    plotChart(like_trend_data, 'Likes', likeContext)
    plotChart(view_trend_data, 'Views', viewContext)
})
</script>
<script>
import GuestLayout from '@/Layouts/GuestLayout.vue';
export default {
    layout: GuestLayout
}
</script>

<template>
    <Head title="Video Statistics" />

    <div class="container py-5 my-5">
        <hr class="mb-0">
        <div class="text-end">
            <Link :href="route('videos.index')">Back to videos list</Link>
        </div>
        <div class="mb-5">
            <h4 class="my-4 text-uppercase">View trend</h4>
            <canvas id="views_trendline"></canvas>
        </div>
        <hr>
        <div class="mb-5">
            <h4 class="my-4 text-uppercase">Like trend</h4>
            <canvas id="likes_trendline"></canvas>
        </div>
        <hr>
        <div class="mb-5">
            <h4 class="my-4 text-uppercase">Comment trend</h4>
            <canvas id="comments_trendline"></canvas>
        </div>
        <div class="text-end">
            <Link :href="route('videos.index')">Back to videos list</Link>
        </div>
    </div>

    <button id="myBtn">
        <img :src="upArrow" alt="alternative" />
    </button>
</template>

<style scoped>
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

hr {
    border: none;
    height: 1px;
    background-color: rgba(0, 0, 0, 1);
}
</style>
