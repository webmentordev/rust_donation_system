<template>
    <section class="w-full">
        <h1 class="text-3xl font-semibold pb-3 border-b border-gray-100">Servers List</h1>
        <form @submit.prevent="uploadHandler" enctype="multipart/form-data" class="flex items-center py-6">
            <Success v-if="isSuccess" text="Server has been created!" />
            <Loading v-if="isLoading" text="Posting data..." />
            <div class="w-full mr-2">
                <Label text="Server name" />
                <Input placeholder="Server Name" v-model="name" required />
            </div>
            <div class="w-full mr-2">
                <Label text="Server Image <500KB*" />
                <Input type="file" @change="uploadFile" accept="image/*" required />
            </div>
            <div class="mt-6">
                <Button text="Create"/>
            </div>
        </form>
        <div v-if="servers.length">
            <div class="bg-dark p-6 rounded-lg mb-3 flex relative" v-for="(server, index) in servers" :key="index">
                <ServerItem :server="server" @clickEvent="refreshData" />
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

    const refreshData = () => {
        fetchServer();
    }

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