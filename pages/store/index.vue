<template>
    <section class="w-full">
        <h1 class="text-3xl font-semibold pb-3 text-center">Our Servers Store</h1>
        <div class="max-w-4xl m-auto grid grid-cols-3 gap-5 py-6" v-if="servers.length">
            <div class="w-full bg-dark overflow-hidden rounded-lg p-3" v-for="(server, index) in servers" :key="index">
                <NuxtImg :src="server.image" width="150px" quality="20" class="rounded-lg w-full" />
                <div class="p-3">
                    <h3 class="text-white font-semibold price text-center mb-2 text-lg">{{ server.name }}</h3>
                    <LinkButton :to='"/store/"+server.slug' text="Visit store" />
                </div>
            </div>
        </div>
        <p v-else class="text-lg text-center">{{ loadedStatus }}</p>
    </section>
</template>

<script setup>
    import axios from 'axios';
    axios.defaults.withCredentials = true;
    
    const servers = ref([]);
    const loadedStatus = ref("Loading...")
    const config = useRuntimeConfig();
    axios.defaults.baseURL = config.public.url
    
    definePageMeta({
        layout: 'custom'
    })
    
    onMounted(() => {
        fetchServer();
    })
    function fetchServer(){
        axios.get('/api/servers/fetch')
        .then(result => {
            servers.value = result.data.data
            if(servers.value.length){
                loadedStatus.value = "Loaded"
            }else{
                loadedStatus.value = "No server data exist."
            }
        });
    }
</script>