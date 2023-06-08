<template>
    <nuxt-link class="bg-rust py-3 px-4 w-full inline-block text-white text-center rounded-md font-semibold">{{ text }}</nuxt-link>
</template>

<script setup>
    defineProps(['text'])
</script>