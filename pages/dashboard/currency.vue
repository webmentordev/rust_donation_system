<template>
    <section class="w-full">
        <h1 class="text-3xl font-semibold pb-3 border-b border-gray-100">Currencies List</h1>
        <form @submit.prevent="uploadHandler" enctype="multipart/form-data" class="flex items-center py-6">
                <Success v-if="isSuccess" text="Currency has been created!" />
                <Loading v-if="isLoading" text="Posting data..." />
                <div class="w-full mr-2">
                    <Input placeholder="Currency Code (like USD, EUR)" v-model="code" required />
                </div>
                <div class="w-full mr-2">
                    <Input placeholder="Currency Symbol (like $)" v-model="symbol" required />
                </div>
                <Button type="submit" text="Create"/>
            </form>
        <div v-if="currencies.length" class="rounded-lg mb-3 overflow-hidden">
            <table class="w-full">
                <tr class="bg-black text-white">
                    <th class="p-2 text-start">ID #</th>
                    <th class="text-start">Code</th>
                    <th class="text-start">Symbol</th>
                    <th class="p-2 text-end">Created</th>
                </tr>
                <tr v-for="(currency, index) in currencies" :key="index" class="odd:bg-gray-100">
                    <td class="p-2 text-start">{{ currency.id }}</td>
                    <td class="text-start">{{ currency.code }}</td>
                    <td class="text-start">{{ currency.symbol }}</td>
                    <td class="p-2 text-end">{{ currency.created }}</td>
                </tr>
            </table>
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
    
    const code = ref("");
    const symbol = ref("");
    const currencies = ref([]);
    const isLoading = ref(false);
    const isSuccess = ref(false);
    const loadedStatus = ref("Loading...")
    
    onMounted(() => {
        fetchCurrency();
    })

    function fetchCurrency(){
        axios.get('/api/currencies')
        .then(result => {
            currencies.value = result.data.data
            if(currencies.value.length){
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
            code: code.value,
            symbol: symbol.value,
        };
        axios.get('/sanctum/csrf-cookie')
        .then(() => {
            axios.post('/api/currencies', formData).then(() => {
                isSuccess.value = true;
                isLoading.value = false;
                fetchCurrency();
                setTimeout(() => {
                    isSuccess.value = false;
                }, 3000);
            }).catch(() => {
                isLoading.value = false;
            });
        }).catch((error) => {
            console.log(error)
        });
    }
</script>