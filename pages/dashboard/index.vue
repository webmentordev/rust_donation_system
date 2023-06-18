<template>
    <section class="w-full">
        <h1 class="text-3xl font-semibold pb-3 border-b border-gray-100">Welcome to Dashboard 👋</h1>
    </section>
</template>

<script setup>
    definePageMeta({
        middleware: 'auth'
    });
</script>