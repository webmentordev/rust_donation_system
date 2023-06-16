<template>
    <section class="w-full py-6">
        <div class="max-w-[450px] w-full rounded-lg" v-if="product">
            <h1 class="font-bold text-3xl mb-3">Final step, make the payment</h1>
            <Loading v-if="isLoading" text="Processing..." />
            <div class="bg-gray-100 p-8 py-12  rounded-lg w-full mb-3">
                <h3 class="text-slate-500/80 font-semibold text-[15px] mb-3">You've to pay</h3>
                <span class="price font-semibold text-5xl mb-3">{{ product.currency.symbol }}{{ product.price }}</span>
                <div class="flex mt-3 mb-1">
                    <img src="https://api.iconify.design/ic:round-check-circle.svg?color=%2321b039" width="20" alt="Check">
                    <span class="font-semibold ml-2">How to claim?</span>
                </div>
                <p class="text-[14px] text-slate-500">After completing the payment, join the server and type <span class="p-1 px-3 bg-gray-200 rounded-lg inline-block">/claimdonation</span> in-game chat. Enjoy your perks & rank.</p>
                <div class="flex mt-3 mb-2">
                    <img src="https://api.iconify.design/ic:round-check-circle.svg?color=%2321b039" width="20" alt="Check">
                    <span class="font-semibold ml-2">Payment & Invoices</span>
                </div>
                <p class="text-[14px] text-slate-500">We'll worry about all the transctions and payment. You can sit back and relax while you enjoy your wipe.</p>

                <div class="flex mt-3 mb-2">
                    <img src="https://api.iconify.design/ic:round-check-circle.svg?color=%2321b039" width="20" alt="Check">
                    <span class="font-semibold ml-2">Discounts & Offers</span>
                </div>
                <p class="text-[14px] text-slate-500">You'll be provided with the offers from time to time and also have perks and benefits.</p>
            </div>
            <Button @click="checkout" text="Checkout" />
            <p class="text-center mt-6 flex items-center justify-center text-gray-500">Powered by <NuxtImg class="ml-2" src="https://api.iconify.design/logos:stripe.svg?color=%23f33f12" width="50px" /></p>
        </div>
        <p v-else class="text-lg text-center">{{ loadedStatus }}</p>
    </section>
</template>

<script setup>
    import axios from 'axios';
    axios.defaults.withCredentials = true;
    
    definePageMeta({
        layout: 'auth-layout'
    })

    const isLoading = ref(false);
    const loadedStatus = ref("Loading...")
    const product = ref();
    const data = useRoute().params;
    const config = useRuntimeConfig();
    axios.defaults.baseURL = config.public.url

    const token = useAuthState();

    onMounted(() => {
        fecthData();
    })

    function fecthData(){
        axios.get(`/api/product/${data.product}`)
        .then(result => {
            product.value = result.data.data[0]
            if(result.status == 200){
                loadedStatus.value = "Loaded"
            }else{
                loadedStatus.value = "No product data exist."
            }
        }).catch(error => console.log(error));
    }

    const checkout = () => {
        isLoading.value = true;
        if(token.value == null){
            isLoading.value = false;
            useRouter().push('/auth/login');
        }
        axios.get('/sanctum/csrf-cookie')
        .then(() => {
            console.log(token.value)
            axios.post(`/api/checkout/${data.product}`, { login: "LoginAttempt" }, { headers: { 'Authorization': 'Bearer ' + token.value } })
            .then((result) => {
                isLoading.value = false;
                if(result.status == 201){
                    navigateTo(result.data.redirect, {external: true})
                }
            }).catch((error) => {
                isLoading.value = false;
                console.log(error)
            });
        }).catch((error) => {
            isLoading.value = false;
            console.log(error)
        });
    }
</script>