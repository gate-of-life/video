<script setup>
import { onMounted } from 'vue'
import ChannelCard from '@/Components/Channel/ChannelCard.vue'
import useScroller from '@/Composables/useScroller'

defineProps({
    channels: Object,
    most_subscriber: Object,
    most_video: Object,
    most_viewed: Object,
    trending: Object,
})

onMounted(() => {
    useScroller()
})
</script>
<script>
import GuestLayout from '@/Layouts/GuestLayout.vue';
export default {
    layout: GuestLayout
}
</script>

<template>
    <Head title="Channels" />

    <div class="py-5 mt-5">
        <div class="container">
            <h5>Weekly chart</h5>
            <div class="row">
                <div class="col-md-4 col-lg-3">
                    <div>Most subscribers</div>
                    <ChannelCard :channel="most_subscriber.channel" />
                </div>
                <div class="col-md-4 col-lg-3">
                    <div>Most videos</div>
                    <ChannelCard :channel="most_video.channel" />
                </div>
                <div class="col-md-4 col-lg-3">
                    <div>Most viewed</div>
                    <ChannelCard :channel="most_viewed.channel" />
                </div>
                <div class="col-md-4 col-lg-3">
                    <div>Trending</div>
                    <ChannelCard :channel="trending.channel" />
                </div>
            </div>
            <hr>
            <div class="row">
                <template v-if="Object.keys(channels).length">
                    <div class="col-md-4 col-lg-3" v-for="channel in channels" :key="channel.id">
                        <ChannelCard :channel="channel" />
                    </div>
                </template>
            </div>
        </div>
    </div>
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
