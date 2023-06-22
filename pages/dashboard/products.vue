<template>
    <section class="w-full">
        <h1 class="text-3xl font-semibold pb-3 border-b border-gray-100">Products List</h1>
        <form @submit.prevent="uploadHandler" enctype="multipart/form-data" class="grid grid-cols-2 gap-3 py-6">
                <Success v-if="isSuccess" text="Product has been created!" />
                <Loading v-if="isLoading" text="Posting data..." />
                <div class="w-full mr-2">
                    <Label text="Product name" />
                    <Input placeholder="Product Name" v-model="name" required />
                    <ErrorMessage v-if="errors" :text="errors.name" />
                </div>
                <div class="w-full mr-2">
                    <Label text="Server" />
                    <Select v-model="server_id" required>
                        <option value="" selected>—Select server—</option>
                        <option v-if="servers.length" v-for="(server, index) in servers" :key="index" :value="server.id">{{ server.name }}</option>
                        <option v-else value="">Please create a server first</option>
                    </Select>
                    <ErrorMessage v-if="errors" :text="errors.server_id" />
                </div>
                <div class="w-full">
                    <Label text="Product Currency" />
                    <CurrencyItem v-model="currency" required />
                    <ErrorMessage v-if="errors" :text="errors.currency" />
                </div>
                
                <div class="w-full">
                    <Label text="Product Price" />
                    <Input type="number" v-model="price" step="0.01" required />
                    <ErrorMessage v-if="errors" :text="errors.price" />
                </div>
                <div class="w-full col-span-2">
                    <Label text="Product Image <500KB*" />
                    <Input type="file" @change="uploadFile" accept="image/*" required />
                    <ErrorMessage v-if="errors" :text="errors.image" />
                </div>
                <div class="w-full col-span-2 mb-16">
                    <Quill v-model:content="description"/>
                    <ErrorMessage v-if="errors" :text="errors.description" />
                </div>
                <div class="w-full">
                    <Button type="submit" text="Create Product"/>
                </div>
            </form>
        <div v-if="products.length" class="bg-dark p-6 rounded-lg mb-3 flex relative" v-for="(product, index) in products" :key="index">
            <ProductItem :product="product" @clickEvent="refreshData" />
        </div>
        <p v-else class="text-lg text-center mt-3">{{ loadedStatus }}</p>
    </section>
</template>

<script setup>
    import axios from 'axios';
    axios.defaults.withCredentials = true;
    axios.defaults.baseURL = useRuntimeConfig().public.url;

    definePageMeta({
        middleware: 'auth'
    });
    
    const name = ref("")
    const price = ref(0);
    const server_id = ref("");
    const file = ref("");
    const currency = ref("");
    const description = ref("");
    const servers = ref([]);
    const products = ref([]);
    const isLoading = ref(false);
    const isSuccess = ref(false);
    const loadedStatus = ref("Loading...")
    const errors = ref(null);
    const errorMessage = ref(null);
    
    onMounted(() => {
        fetchServer();
        fetchProducts();
    })

    const refreshData = () => {
        fetchProducts();
    }

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
                loadedStatus.value = "No products data exist."
            }
        });
    }

    function uploadHandler(){
        isLoading.value = true;
        isSuccess.value = false;
        errors.value = null;
        errorMessage.value = null;
        const formData = {
            name: name.value,
            image: file.value,
            server_id: server_id.value,
            price: price.value,
            description: description.value,
            currency: currency.value
        };
        axios.get('/sanctum/csrf-cookie')
        .then(() => {
            axios.post('/api/products', formData, {
                headers: { "Content-Type": "multipart/form-data" }
            }).then(() => {
                isSuccess.value = true;
                isLoading.value = false;
                fetchProducts();
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