<script setup>
import List from '@/Components/List.vue'
import SuccessMessage from '@/Components/SuccessMessage.vue'
import Pagination from '@/Components/Pagination.vue'
import ErrorMessage from '@/Components/ErrorMessage.vue'
import { useForm } from '@inertiajs/vue3'
const props = defineProps({
    channels: Object,
    videos: Object,
    channel_id: Number
})
const filterForm = useForm({
    channel_id: props.channel_id
})
const filter = () => {
    filterForm.post(route('user.videos.filter'))
}
</script>

<script>
import UserLayout from '@/Layouts/UserLayout.vue';
export default {
    layout: UserLayout
}
</script>

<template>
    <Head title="Videos" />

    <SuccessMessage />
    <ErrorMessage />

    <List>
        <template v-slot:title>
            <div class="col-md-4 mb-2 mb-md-0">
                <h4 class="font-weight-normal">Videos</h4>
            </div>
            <div class="col-md-8 text-right">
                <form @submit.prevent="filter">
                    <select id="channel_id" name="channel_id" class="form-control" @change="filter"
                        v-model="filterForm.channel_id" required>
                        <option value="">Channel</option>
                        <option v-for="channel in channels" :value="channel.id" :key="channel.id">
                            {{ channel.title }}
                        </option>
                    </select>
                </form>
            </div>
        </template>
        <template v-slot:thead>
            <tr>
                <th>#</th>
                <th>Video ID</th>
                <th>Title</th>
                <th>Date published</th>
                <th></th>
            </tr>
        </template>
        <template v-if="Object.keys(videos.data).length" v-slot:tbody>
            <tr v-for="video, index in videos.data" :key="video.id">
                <td>{{ (videos.current_page - 1) * videos.per_page + index + 1 }}</td>
                <td>{{ video.video_id }}</td>
                <td>{{ video.title }}</td>
                <td>{{ new Date(video.published_at).toLocaleString('en-us', {
                    month: 'long', day: 'numeric', year: 'numeric'
                }) }}</td>
                <td>
                    <Link :href="route('user.videos.show', video.id)">Show
                    </Link>
                </td>
            </tr>
        </template>
        <template v-else v-slot:tbody>
            <tr>
                <td colspan="6" class="text-center">
                    No videos yet
                </td>
            </tr>
        </template>
        <template v-slot:buttons>
            <div class="card p-2 my-2">
                <pagination class="mt-6" :links="videos.links" />
            </div>
        </template>
    </List>
</template>