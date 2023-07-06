<script setup>
</script>

<template>
    <div v-show="$page.props.flash.error">
        <p class="text-white bg-red p-2 rounded">
            {{ $page.props.flash.error }}
        </p>
    </div>
</template>
