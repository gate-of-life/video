<script setup>
import SuccessMessage from '@/Components/SuccessMessage.vue'
import ErrorMessage from '@/Components/ErrorMessage.vue'
import { useForm } from '@inertiajs/vue3'
import { ref } from 'vue'
import List from '@/Components/List.vue'
defineProps({
    channels: Object,
    channel_categories: Object,
    can: Object
})
const form = useForm({
    channel_id: '',
    channel_category_id: []
})
const isChannelCategoriesValid = ref(true)
const validateChannelCategories = () => {
    isChannelCategoriesValid.value = form.channel_category_id.length > 0
}
const submit = () => {
    validateChannelCategories()
    if (isChannelCategoriesValid.value) {
        form.post(route('user.channels.store'), {
            onFinish: () => {
                form.reset()
            },
        })
    }
}
</script>

<script>
import UserLayout from '@/Layouts/UserLayout.vue';
import { useForm } from '@inertiajs/vue3';
export default {
    layout: UserLayout
}
</script>

<template>
    <Head title="Channels" />

    <div class="pt-2">
        <SuccessMessage />
        <ErrorMessage />
        <p v-show="form.errors.channel_id" class="text-white bg-red p-2 rounded">
            {{ form.errors.channel_id }}
        </p>
    </div>
    <List>
        <template v-slot:title>
            <form @submit.prevent="submit">
                <template v-if="can.create_channel">
                    <input class="form-control me-2" type="text" placeholder="Insert channel ID" v-model="form.channel_id"
                        required>
                    <p class="mb-0 pt-3">Select channel categories</p>
                    <v-select multiple :options="channel_categories" label="name"
                        :reduce="channel_category => channel_category.id" v-model="form.channel_category_id">
                    </v-select>
                    <p v-if="!isChannelCategoriesValid" class="validation-error">
                        Please select at least one channel category.
                    </p>
                    <div class="card mt-3">
                        <button type="submit" class="btn btn-primary">Add channel
                        </button>
                    </div>
                </template>
                <div v-else>
                    <h4>Channels</h4>
                </div>
            </form>
        </template>
        <template v-slot:thead>
            <h6>Channels</h6>
            <tr>
                <th>#</th>
                <th>Channel ID</th>
                <th>Custom ID</th>
                <th>Title</th>
                <th>Date published</th>
                <th></th>
            </tr>
        </template>
        <template v-if="Object.keys(channels).length" v-slot:tbody>
            <tr v-for="channel, index in channels" :key="channel.id">
                <td>{{ index + 1 }}</td>
                <td>{{ channel.channel_id }}</td>
                <td>{{ channel.custom_url }}</td>
                <td>{{ channel.title }}</td>
                <td>{{ new Date(channel.published_at).toLocaleString('en-us', {
                    month: 'long', day: 'numeric', year: 'numeric'
                }) }}</td>
                <td>
                    <Link :href="route('user.channels.show', channel.id)">Show
                    </Link>
                </td>
            </tr>
        </template>
        <template v-else v-slot:tbody>
            <tr>
                <td colspan="6" class="text-center">
                    No channels yet
                </td>
            </tr>
        </template>
    </List>
</template>

<style scoped>
.validation-error {
    color: red;
    font-size: 14px;
}
</style>