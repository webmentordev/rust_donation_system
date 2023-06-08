<template>
    <div>
        <NuxtLink to="/store/asdasd">GOGO</NuxtLink>
    </div>
</template>

<script setup>
    import axios from 'axios';
    axios.defaults.withCredentials = true;
    
    const server = ref([]);
    const config = useRuntimeConfig();
    const { slug } = useRoute().params;
    axios.defaults.baseURL = config.public.url

    definePageMeta({
        layout: 'custom'
    });
    
    onMounted(async () => {
        axios.get(`/api/servers/fetch/single/${slug}`)
        .then(result => {
            server.value = result.data.data
            if(server.value.length == 0){
                throw createError({ statusCode: 404, statusMessage: 'Server not found!', fatal: true })
            }
        }).catch(() => {
            throw createError({ statusCode: 404, statusMessage: 'Server not found!', fatal: true })
        });
    })
</script>