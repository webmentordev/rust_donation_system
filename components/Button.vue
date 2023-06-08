<template>
    <button class="bg-rust h-fit text-white inline-block py-2 px-4 rounded-md">
        {{ text }}
    </button>
</template>

<script setup>
    defineProps(['text'])
</script>