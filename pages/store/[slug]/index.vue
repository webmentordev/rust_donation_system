<template>
    <section class="w-full">
        <h1 class="text-3xl font-semibold pb-3 text-center">Server Packages</h1>
        <div class="max-w-4xl m-auto grid grid-cols-3 gap-5 py-6" v-if="products.length">
            <div class="w-full bg-dark overflow-hidden rounded-lg p-3" v-for="(product, index) in products" :key="index">
                <ListingItem :product="product" />
            </div>
        </div>
        <p v-else class="text-lg text-center">{{ loadedStatus }}</p>
    </section>
</template>

<script setup>
    import axios from 'axios';
    axios.defaults.withCredentials = true;
    
    const products = ref([]);
    const config = useRuntimeConfig();
    const { slug } = useRoute().params;
    const loadedStatus = ref("Loading...")
    axios.defaults.baseURL = config.public.url

    definePageMeta({
        layout: 'custom'
    });
    onMounted(async () => {
        axios.get(`/api/servers/fetch/single/${slug}`)
        .then(result => {
            products.value = result.data.data
            if(products.value.length == 0){
                throw createError({ statusCode: 404, statusMessage: 'Server not found!', fatal: true })
            }else{
                loadedStatus.value = "Data Loaded!"
            }
        }).catch(() => {
            loadedStatus.value = "No package data exist."
            throw createError({ statusCode: 404, statusMessage: 'Server not found!', fatal: true })
        });
    })
</script>