<script setup>
import { onMounted, onUnmounted, ref } from 'vue'
import VideoCard from '@/Components/Video/VideoCard.vue'
import Loader from '@/Components/Loader.vue'
import useScroller from '@/Composables/useScroller'
import { router } from '@inertiajs/vue3'

const props = defineProps({
    videos: Object,
    most_commented: Object,
    most_liked: Object,
    most_viewed: Object
})
const allVideos = ref([])
let isLoading = ref(false)
let isAllPagesLoaded = ref(false)
const loadVideos = (url = route('videos.index')) => {
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
            window.history.replaceState({}, page.props.url, '/videos')
            if (!page.props.videos.next_page_url) {
                isAllPagesLoaded.value = true
            }
            isLoading.value = false
        },
    })
}
const handleScroll = () => {
    const scrollHeight = document.documentElement.scrollHeight;
    const scrollTop = document.documentElement.scrollTop || document.body.scrollTop;
    const clientHeight = document.documentElement.clientHeight;

    if (scrollHeight - scrollTop - clientHeight < 200 && !isLoading.value) {
        loadVideos(props.videos.next_page_url)
    }
}
onMounted(() => {
    loadVideos()
    window.addEventListener('scroll', handleScroll)
    useScroller()
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
    <Head title="Videos" />

    <div class="py-5 mt-5">
        <div class="bg-light-gray">
            <div class="container">
                <h5>Weekly chart</h5>
                <div class="row">
                    <div class="col-md-6 col-lg-4">
                        <div>Most viewed</div>
                        <VideoCard :video="most_viewed.video" />
                    </div>
                    <div class="col-md-6 col-lg-4">
                        <div>Most liked</div>
                        <VideoCard :video="most_liked.video" />
                    </div>
                    <div class="col-md-6 col-lg-4">
                        <div>Most commented</div>
                        <VideoCard :video="most_commented.video" />
                    </div>
                </div>
                <hr>
                <div class="row">
                    <template v-if="allVideos">
                        <div class="col-md-6 col-lg-4" v-for="video in allVideos" :key="video.id">
                            <VideoCard :video="video" />
                        </div>
                    </template>
                </div>
            </div>
        </div>
    </div>
    <Loader v-if="isLoading" />
    <button id="myBtn">
        <img :src="'image/up-arrow.png'" alt="alternative" />
    </button>
</template>

<style scoped>
/******************************/
/*     Back To Top Button     */
/******************************/
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
    height: 2.5px;
    border-color: #000;
}
</style>
