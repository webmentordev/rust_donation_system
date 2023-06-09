<template>
    <section class="w-full">
        <div class="flex pb-3 border-b border-gray-100 justify-between items-center">
            <h1 class="text-3xl font-semibold">Products List</h1>
            <Button @click="popUp = !popUp" text="Create" />
        </div>
    </section>
    <section v-if="popUp" @click.self="popUp = false" class="fixed top-0 left-0 bg-dark bg-opacity-90 backdrop-blur h-screen w-full flex items-center justify-center">
        <div class="bg-white py-6 max-w-2xl p-6 rounded-lg">
            <h2 class="font-bold mb-3 text-3xl text-center">Create Server Product</h2>
            <form @submit.prevent="uploadHandler" enctype="multipart/form-data" class="grid grid-cols-2 gap-3">
                <Success v-if="isSuccess" text="Product has been created!" />
                <Loading v-if="isLoading" text="Posting data..." />
                <div class="w-full mr-2">
                    <Label text="Product name" />
                    <Input placeholder="Product Name" v-model="name" required />
                </div>
                <div class="w-full mr-2">
                    <Label text="Server" />
                    <Select v-model="server_id" v-if="servers.length" required>
                        <option value="" selected>—Select server—</option>
                        <option v-for="(server, index) in servers" :key="index" :value="server.id">{{ server.name }}</option>
                    </Select>
                </div>
                <div class="w-full">
                    <Label text="Product Currency" />
                    <Currency v-model="currency" required />
                </div>
                
                <div class="w-full">
                    <Label text="Product Price" />
                    <Input type="number" v-model="price" step="0.01" required />
                </div>

                <div class="w-full col-span-2">
                    <Label text="Product Image <500KB*" />
                    <Input type="file" @change="uploadFile" accept="image/*" required />
                </div>
                <div class="w-full col-span-2">
                    <Label text="Description" />
                    <TextArea v-model="description" placeholder="Write description" required />
                </div>
                <div class="w-full">
                    <Button type="submit" text="Create Product"/>
                </div>
            </form>
        </div>
    </section>
</template>

<script setup>
    import axios from 'axios';
    axios.defaults.withCredentials = true;

    const name = ref("")
    const price = ref("");
    const server_id = ref("");
    const file = ref("");
    const currency = ref("");
    const description = ref("");
    
    const servers = ref([]);
    const products = ref([]);
    
    const isLoading = ref(false);
    const isSuccess = ref(false);
    const popUp = ref(false)
    
    const loadedStatus = ref("Loading...")
    
    const config = useRuntimeConfig();
    axios.defaults.baseURL = config.public.url

    onMounted(() => {
        fetchServer();
        fetchProducts();
    })

    const uploadFile = (event) => {
        file.value = event.target.files[0];
    };

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

    function fetchProducts(){
        axios.get('/api/products')
        .then(result => {
            products.value = result.data.data
            if(products.value.length){
                loadedStatus.value = "Loaded"
            }else{
                loadedStatus.value = "No server data exist."
            }
        });
    }

    function uploadHandler(){
        isLoading.value = true;
        isSuccess.value = false;
        const formData = {
            name: name.value,
            image: file.value,
            server_id: server_id.value,
            price: price.value,
            description: description.value,
            currency: currency.value
        };
        console.log(formData)
        axios.get('/sanctum/csrf-cookie')
        .then(() => {
            axios.post('/api/product', formData, {
                headers: { "Content-Type": "multipart/form-data" }
            }).then(() => {
                isSuccess.value = true;
                isLoading.value = false;
                fetchServer();
            }).catch(() => {
                isLoading.value = false;
            });
        }).catch((error) => {
            console.log(error)
        });
    }
</script>