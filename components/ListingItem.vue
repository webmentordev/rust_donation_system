<template>
    <NuxtImg :src="product.image" width="150px" quality="20" class="rounded-lg w-full" />
    <div class="p-3">
        <h3 class="text-white font-semibold price text-center mb-2 text-lg">{{ product.name }}</h3>
        <LinkButton :to='"/store/"+server.slug+"/"+product.slug' text="Visit store" />
    </div>
</template>

<script setup>
    defineProps(['server', 'product'])
</script>