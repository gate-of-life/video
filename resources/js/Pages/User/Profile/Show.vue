<script setup>
defineProps({
    user: Object
})
</script>
<script>
import UserLayout from '@/Layouts/UserLayout.vue'
export default {
    layout: UserLayout
}
</script>
    
<template>
    <div class="pt-3">
        <div class="card">
            <div class="row">
                <div class="col-md-6 offset-md-3">
                    <div class="text-center my-5">
                        <img v-if="user.image" :src="'https://gateoflife.net/storage/user/image/' + user.image"
                            class="rounded" style="width: 25%" alt="">
                        <img v-else :src="'/storage/user/image/default.png'" class="rounded" style="width: 25%" alt="image">
                    </div>
                    <ul class="list-group list-group-unbordered">
                        <li class="list-group-item">
                            Name <a class="float-right my-2">{{ user.name }}</a>
                        </li>
                        <li class="list-group-item">
                            Phone <a class="float-right my-2">{{ user.phone }}</a>
                        </li>
                        <li class="list-group-item my-2">
                            Email <a class="float-right">{{ user.email }}</a>
                        </li>
                    </ul>
                    <div class="text-right my-5">
                        <Link :href="route('user.profile.edit')" class="btn btn-outline-primary">Edit
                        </Link>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
    