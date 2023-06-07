<template>
    <section class="fixed bottom-3 left-3 p-6 rounded-lg bg-black text-white flex items-center shadow-lg">
        <img src="https://api.iconify.design/svg-spinners:90-ring-with-bg.svg?color=%23ffffff" width="30" alt="Spinner">
        <p class="ml-2 text-lg price font-semibold">{{ text }}</p>
    </section>
</template>
<script setup>
    defineProps(['text'])
</script>