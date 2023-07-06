<script setup>
import InputCard from '@/Components/InputCard.vue'
import InputError from '@/Components/InputError.vue'
import { useForm } from '@inertiajs/vue3'
defineProps({
    channels: Object
})
const form = useForm({
    channel_id: ''
})
const submit = () => {
    form.post(route('user.videos.filter'), {
        onFinish: () => {
            form.reset()
        },
    })
}
</script>

<script>
import UserLayout from '@/Layouts/UserLayout.vue';
export default {
    layout: UserLayout
}
</script>

<template>
    <Head title="Channels" />

    <form @submit.prevent="submit">
        <div class="row justify-content-center mt-5 pt-5">
            <div class="col-md-6">
                <InputCard title="Select Channel">
                    <div class="container-fluild form-group">
                        <div class="input-group mb-4">
                            <select id="channel_id" name="channel_id" class="form-control" v-model="form.channel_id"
                                required>
                                <option value="" selected>Channel</option>
                                <option v-for="channel in channels" :value="channel.id" :key="channel.id">
                                    {{ channel.title }}
                                </option>
                            </select>
                        </div>
                        <InputError :message="form.errors.channel_id"></InputError>
                    </div>
                    <div class="text-right pt-3 mb-3">
                        <button type="submit" class="btn btn-primary mr-2">Filter
                        </button>
                    </div>
                </InputCard>
            </div>
        </div>
    </form>
</template>