<template>
    <NuxtImg :src="product.image" width="150px" quality="20" class="rounded-lg w-full" />
    <div class="p-3">
        <h3 class="text-white font-semibold price text-center mb-2 text-lg">{{ product.name }}</h3>
        <div class="flex">
            <span class="p-2 px-3 mr-2 cursor-pointer rounded-lg bg-white flex items-center justify-center group"><NuxtImg width="25" src="https://api.iconify.design/ant-design:exclamation-circle-outlined.svg?color=%231d1b1b" />
            <p class="description hidden group-hover:block text-sm fixed w-[500px] p-6 bottom-3 right-3 shadow-lg z-30 bg-white rounded-lg" v-html="product.description"></p>
            </span>
            <LinkButton :to='"/store/package/"+product.slug' :text='"Pay "+ product.price + product.currency.symbol' />
        </div>
    </div>
</template>

<script setup>
    defineProps(['product'])
</script>