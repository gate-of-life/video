<script setup>
import formatCount from '@/Composables/formatCount'
defineProps({
    channel: Object,
    channel_stat: {
        type: Object,
        default: null
    }
})
</script>

<template>
    <a :href="route('channels.show', channel.id)">
        <div class="channel-card">
            <div class="card me-lg-3 pb-5">
                <div class="text-center">
                    <img class="mt-4 p-3 rounded-circle" :src="channel.thumbnail_high" alt="">
                </div>
                <div class="card-body mx-2">
                    <div class="text-center">
                        <div class="text-bold">{{ channel.title }}</div>
                        <div>{{ formatCount(channel.subscriber_count) }} subscribers</div>
                        <div class="text-center">
                            <hr class="mx-auto">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </a>
</template>

<style scoped>
.channel-card .card {
    height: 20rem;
}

.channel-card img {
    width: 50%;
}

.channel-card h2 {
    margin-bottom: 3rem;
}

.channel-card .card h4 {
    font-family: 'Roboto Condensed', sans-serif;
    font-weight: 700;
}

a {
    text-decoration: none;
    color: #333333;
}

.channel-card .card p {
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
</style>