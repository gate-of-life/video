<script setup>
import VideoCard from '@/Components/Video/VideoCard.vue'
import ChannelCard from '@/Components/Channel/ChannelCard.vue'
const props = defineProps({
    most_viewed_videos: Object,
    most_liked_videos: Object,
    most_commented_videos: Object,
    most_viewed_channels: Object,
    trending_channels: Object,
})
</script>
<script>
import GuestLayout from '@/Layouts/GuestLayout.vue';
export default {
    layout: GuestLayout
}
</script>

<template>
    <Head title="Home" />

    <div class="pt-5 mt-5">
        <div class="container">
            <div class="row">
                <h5 class="my-3">Videos: Most viewed</h5>
                <template v-if="most_viewed_videos">
                    <div class="col-md-6 col-lg-4" v-for="video in most_viewed_videos" :key="video.id">
                        <VideoCard :video="video.video" />
                    </div>
                </template>
            </div>
            <hr>
            <div class="row">
                <h5 class="my-3">Videos: Most liked</h5>
                <template v-if="most_liked_videos">
                    <div class="col-md-6 col-lg-4" v-for="video in most_liked_videos" :key="video.id">
                        <VideoCard :video="video.video" />
                    </div>
                </template>
            </div>
            <hr>
            <div class="row">
                <h5 class="my-3">Videos: Most commented</h5>
                <template v-if="most_commented_videos">
                    <div class="col-md-6 col-lg-4" v-for="video in most_commented_videos" :key="video.id">
                        <VideoCard :video="video.video" />
                    </div>
                </template>
            </div>
            <hr>
            <div class="row">
                <h5 class="my-3">Channels: Most viewed</h5>
                <template v-if="most_viewed_channels">
                    <div class="col-md-6 col-lg-4" v-for="channel in most_viewed_channels" :key="channel.id">
                        <ChannelCard :channel="channel.channel" />
                    </div>
                </template>
            </div>
            <hr>
            <div class="row">
                <h5 class="my-3">Channels: Trending</h5>
                <template v-if="trending_channels">
                    <div class="col-md-6 col-lg-4" v-for="channel in trending_channels" :key="channel.id">
                        <ChannelCard :channel="channel.channel" />
                    </div>
                </template>
            </div>
            <hr>

            <div class="footer row">
                <div class="col-lg-12">
                    <h4>If you want to know us more and get periodic updates
                        then
                        follow us</h4>
                    <div class="social-container">
                        <span class="fa-stack">
                            <a href="#your-link">
                                <i class="fas fa-circle fa-stack-2x"></i>
                                <i class="fab fa-telegram fa-stack-1x"></i>
                            </a>
                        </span>
                        <span class="fa-stack">
                            <a href="#your-link">
                                <i class="fas fa-circle fa-stack-2x"></i>
                                <i class="fab fa-facebook-f fa-stack-1x"></i>
                            </a>
                        </span>
                        <span class="fa-stack">
                            <a href="#your-link">
                                <i class="fas fa-circle fa-stack-2x"></i>
                                <i class="fab fa-twitter fa-stack-1x"></i>
                            </a>
                        </span>
                        <span class="fa-stack">
                            <a href="#your-link">
                                <i class="fas fa-circle fa-stack-2x"></i>
                                <i class="fab fa-youtube fa-stack-1x"></i>
                            </a>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>
hr {
    height: 2.5px;
    border-color: #000;
}

h5 {
    font-size: 1.4rem;
    font-weight: 400;
}

/******************/
/*     Footer     */
/******************/
.footer {
    position: relative;
    padding-top: 7.5rem;
    padding-bottom: 6rem;
    text-align: center;
}

.footer .decoration-circles {
    position: absolute;
    top: 96px;
    left: -60px;
}

.footer div[class*="col"] {
    /* selects all elements which have classes starting with col */
    /* needed so that the absolute positioned decoration stays behind */
    position: relative;
}

.footer a {
    color: #223150;
    text-decoration: none;
}

.footer h4 {
    margin-bottom: 1.875rem;
}

.footer .fa-stack {
    width: 2em;
    margin-bottom: 1.25rem;
    margin-right: 0.375rem;
    font-size: 1.5rem;
}

.footer .fa-stack .fa-stack-1x {
    color: #0092ff;
    transition: all 0.2s ease;
}

.footer .fa-stack .fa-stack-2x {
    color: #ffffff;
    transition: all 0.2s ease;
}

.footer .fa-stack:hover .fa-stack-1x {
    color: #ffffff;
}

.footer .fa-stack:hover .fa-stack-2x {
    color: #0092ff;
}
</style>
