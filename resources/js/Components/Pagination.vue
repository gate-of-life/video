<script setup>
defineProps({
    links: Array
})
</script>

<template>
    <div v-if="links.length > 3">
        <div class="d-flex flex-wrap mb-1">
            <template v-for="(link, key) in links">
                <div v-if="link.url === null" :key="key"
                    class="mb-1 me-1 px-3 py-2 text-gray-400 text-sm leading-4 border rounded" v-html="link.label"></div>
                <Link v-else :key="`link-${key}`"
                    class="mb-1 me-1 px-3 py-2 focus-text-indigo-500 text-sm leading-4 hover-bg-white border focus-border-indigo-500 rounded"
                    :class="{ 'bg-white': link.active }" :href="link.url" v-html="link.label">
                </Link>
            </template>
        </div>
    </div>
</template>
