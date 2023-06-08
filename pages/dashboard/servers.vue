<template>
    <section class="w-full">
        <h1 class="text-3xl font-semibold pb-3 border-b border-gray-100">Servers List</h1>
        <form @submit.prevent="uploadHandler" enctype="multipart/form-data" class="flex items-center py-6">
            <Success v-if="isSuccess" text="Server has been created!" />
            <Loading v-if="isLoading" text="Posting data..." />
            <div class="w-full mr-2">
                <Label text="Server name" />
                <Input placeholder="Server Name" v-model="name" />
            </div>
            <div class="w-full mr-2">
                <Label text="Server Image <500KB*" />
                <Input type="file" @change="uploadFile" accept="image/*" placeholder="Server Name" />
            </div>
            <div class="mt-6">
                <Button text="Create"/>
            </div>
        </form>
        <div v-if="servers.length">
            <div class="bg-dark p-6 rounded-lg mb-3 flex" v-for="(server, index) in servers" :key="index">
                <NuxtImg :src="server.image" width="150px" quality="20" class="rounded-lg" />
                <div class="ml-6">
                    <div class="flex items-center mb-1 border-b py-3 border-white/20">
                        <span class="text-white/60 mr-2">Server</span>
                        <h3 class="font-semibold text-white">{{ server.name }}</h3>
                        <NuxtLink :to='"/store/"+server.slug' class="bg-rust px-2 ml-3 font-semibold rounded-lg text-sm py-[2px]">Visit</NuxtLink>
                    </div>
                    <div class="flex text-white flex-col">
                        <div class="flex items-center">
                            <span class="text-white/60">Token —</span>
                            <p class="p-2 rounded-lg bg-dark text-white token">{{ server.token }}</p>
                        </div>
                        <p class="text-rust">{{ server.created }}</p>
                    </div>
                </div>
            </div>
        </div>
        <p v-else class="text-lg text-center">{{ loadedStatus }}</p>
    </section>
</template>

<script setup>
    import axios from 'axios';
    axios.defaults.withCredentials = true;
    const name = ref('')
    const file = ref();
    const servers = ref([]);
    
    const isLoading = ref(false);
    const isSuccess = ref(false);
    
    const loadedStatus = ref("Loading...")
    
    const config = useRuntimeConfig();
    axios.defaults.baseURL = config.public.url
    
    onMounted(() => {
        fetchServer();
    })

    function fetchServer(){
        axios.get('/api/servers')
        .then(result => {
            servers.value = result.data.data
            if(servers.value.length){
                loadedStatus.value = "Loaded"
            }else{
                loadedStatus.value = "No server data exist."
            }
        });
    }

    const uploadFile = (event) => {
        file.value = event.target.files[0];
    };

    function uploadHandler(){
        isLoading.value = true;
        isSuccess.value = false;
        const formData = {
            name: name.value,
            image: file.value
        };
        axios.get('/sanctum/csrf-cookie')
        .then(() => {
            axios.post('/api/servers', formData, {
                headers: { "Content-Type": "multipart/form-data" }
            }).then(() => {
                isSuccess.value = true;
                isLoading.value = false;
                fetchServer();
            }).catch(() => {
                isLoading.value = false;
            });
        }).catch((error) => {
            error.response.data.message
        });
    }
</script>