<script setup>
defineProps(['title'])
</script>
<template>
    <div class="card">
        <div class="card-header">
            <h4 class="font-weight-normal">{{ title }}</h4>
        </div>
        <div class="card-body">
            <slot />
        </div>
    </div>
</template>
