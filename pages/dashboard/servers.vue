<template>
    <section class="w-full">
        <h1 class="text-3xl font-semibold pb-3 border-b border-gray-100">Servers List</h1>
        <ErrorMessage v-if="errorMessage" :text="errorMessage" />
        <form @submit.prevent="uploadHandler" enctype="multipart/form-data" class="flex items-center py-3 mb-6">
            <Success v-if="isSuccess" text="Server has been created!" />
            <Loading v-if="isLoading" text="Posting data..." />
            
            <div class="w-full mr-2">
                <Label text="Server name" />
                <Input placeholder="Server Name" v-model="name" required />
                <ErrorMessage v-if="errors" :text="errors.name" />
            </div>
            <div class="w-full mr-2">
                <Label text="Server Image <500KB* (825x825)" />
                <Input type="file" @change="uploadFile" accept="image/*" required />
                <ErrorMessage v-if="errors" :text="errors.image" />
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
    axios.defaults.baseURL = useRuntimeConfig().public.url;

    definePageMeta({
        middleware: 'auth'
    });
    
    const name = ref('')
    const file = ref();
    const servers = ref([]);
    const isLoading = ref(false);
    const isSuccess = ref(false);
    const loadedStatus = ref("Loading...")
    const errors = ref(null);
    const errorMessage = ref(null);
    
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
        errors.value = null;
        errorMessage.value = null;
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
                setTimeout(() => {
                    isSuccess.value = false;
                }, 3000);
            }).catch((error) => {
                isLoading.value = false;
                errors.value = error.response.data.errors;
                errorMessage.value = error.response.data.message;
            });
        });
    }
</script>