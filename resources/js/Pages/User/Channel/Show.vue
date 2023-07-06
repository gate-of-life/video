<script setup>
import SuccessMessage from '@/Components/SuccessMessage.vue'
import ErrorMessage from '@/Components/ErrorMessage.vue'
import { ref } from 'vue'
import { useForm } from '@inertiajs/vue3'
const props = defineProps({
    channel: Object,
    channel_categories: Object,
    can: Object
})
const udpateForm = useForm({})
const updateChannel = (id) => {
    udpateForm.put(route('user.channels.update', id))
}
const changeCategoryForm = useForm({
    channel_category_id: props.channel.channel_categories
})
const isChannelCategoriesValid = ref(true)
const validateChannelCategories = () => {
    isChannelCategoriesValid.value = changeCategoryForm.channel_category_id.length > 0
}
const changeCategory = (id) => {
    validateChannelCategories()
    if (isChannelCategoriesValid.value) {
        document.getElementById('close-category').click()
        changeCategoryForm.put(route('user.channels.change_category', id), {
            onFinish: () => {
                changeCategoryForm.reset()
            },
        })
    }
}
const deleteForm = useForm({})
const deleteChannel = (id) => {
    document.getElementById('close-delete').click()
    deleteForm.delete(route('user.channels.destroy', id))
}
</script>

<script>
import UserLayout from '@/Layouts/UserLayout.vue';
export default {
    layout: UserLayout
}
</script>

<template>
    <Head title="Show Channel" />

    <div class="pt-2">
        <SuccessMessage />
        <ErrorMessage />
    </div>
    <h3 class="mb-4 ms-3 pt-4 font-weight-normal">Channel</h3>
    <div class="card px-3 py-4">
        <div class="container card-body table-responsive px-0">
            <table class="table">
                <thead>
                    <tr>
                        <th colspan="2">Basic data</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>ETag</td>
                        <td>{{ channel.etag }}</td>
                    </tr>
                    <tr>
                        <td>Channel ID</td>
                        <td>{{ channel.channel_id }}</td>
                    </tr>
                    <tr>
                        <td>Title</td>
                        <td>{{ channel.title }}</td>
                    </tr>
                    <tr>
                        <td>Description</td>
                        <td>{{ channel.description }}</td>
                    </tr>
                    <tr>
                        <td>Custom URL</td>
                        <td>{{ channel.custom_url }}</td>
                    </tr>
                    <tr>
                        <td>Date published</td>
                        <td>{{ new Date(channel.published_at).toLocaleString('en-us', {
                            month: 'long', day: 'numeric', year: 'numeric'
                        }) }}</td>
                    </tr>
                    <tr>
                        <td>Country</td>
                        <td>{{ channel.country }}</td>
                    </tr>
                    <tr>
                        <td>Key words</td>
                        <td>{{ channel.key_words }}</td>
                    </tr>
                    <tr>
                        <td>Unsubscribed trailer</td>
                        <td>{{ channel.unsubscribed_trailer }}</td>
                    </tr>
                    <tr>
                        <td>Is linked</td>
                        <td>{{ channel.is_linked }}</td>
                    </tr>
                    <tr>
                        <td>Long uploads status</td>
                        <td>{{ channel.long_uploads_status }}</td>
                    </tr>
                    <tr>
                        <td>Made for kids</td>
                        <td>{{ channel.made_for_kids }}</td>
                    </tr>
                    <tr>
                        <td>Privacy status</td>
                        <td>{{ channel.privacy_status }}</td>
                    </tr>
                    <tr>
                        <td>Topic categories</td>
                        <td>{{ channel.topic_categories }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="container card-body table-responsive px-0">
            <table class="table">
                <thead>
                    <tr>
                        <th colspan="2">Thumbnail</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>
                            <div>Default</div><img :src="channel.thumbnail_default" alt="img">
                        </td>
                        <td>
                            <div>Medium</div><img :src="channel.thumbnail_medium" alt="img" height="200">
                        </td>
                        <td>
                            <div>High</div><img :src="channel.thumbnail_high" alt="img" height="400">
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="container card-body table-responsive px-0">
            <table class="table">
                <thead>
                    <tr>
                        <th colspan="2">Channel categories</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Categories</td>
                        <td>{{ channel.channel_categories.map(category => category.name).join(', ') }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    <div class="py-3 d-flex justify-content-end">
        <Link :href="route('user.channels.index')" class="btn btn-primary">Back</Link>
        <form @submit.prevent="updateChannel(channel.id)">
            <button type="submit" class="btn btn-outline-danger mx-2">Update</button>
        </form>
        <a v-if="can.edit_channel" class="btn btn-outline-danger me-2" data-bs-toggle="modal" href="#"
            data-bs-target="#modal-category">Category</a>
        <a v-if="can.delete_channel" class="btn btn-danger" data-bs-toggle="modal" href="#"
            data-bs-target="#modal-delete">Delete</a>
    </div>
    <!-- Change channel category modal -->
    <div class="modal fade modal-danger" id="modal-category">
        <div class="modal-dialog">
            <div class="modal-content">
                <form @submit.prevent="changeCategory(channel.id)">
                    <div class="card mb-0">
                        <div class="card-header">
                            <h4 class="font-weight-normal">Change category</h4>
                        </div>
                        <div class="card-body">
                            <p class="mb-0 pt-3">Select channel categories</p>
                            <v-select multiple :options="channel_categories" label="name"
                                :reduce="channel_category => channel_category.id"
                                v-model="changeCategoryForm.channel_category_id">
                            </v-select>
                            <p v-if="!isChannelCategoriesValid" class="validation-error">
                                Please select at least one channel category.
                            </p>
                            <div class="text-right">
                                <button type="button" class="btn btn-outline-primary m-1" id="close-category"
                                    data-bs-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-danger m-1">Change</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- Delete channel modal -->
    <div class="modal fade modal-danger" id="modal-delete">
        <div class="modal-dialog">
            <div class="modal-content">
                <form @submit.prevent="deleteChannel(channel.id)">
                    <div class="card mb-0">
                        <div class="card-header">
                            <h4 class="font-weight-normal">Delete channel</h4>
                        </div>
                        <div class="card-body">
                            <div class="mb-4">This action cannot be reversed. Are you sure you want to delete this channel?
                            </div>
                            <div class="text-right">
                                <button type="button" class="btn btn-outline-primary m-1" id="close-delete"
                                    data-bs-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-danger m-1">Delete</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>

<style scoped>
.validation-error {
    color: red;
    font-size: 14px;
}
</style>