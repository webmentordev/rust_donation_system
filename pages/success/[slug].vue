<template>
    <section class="w-full">
        <div class="max-w-lg w-fit m-auto">
            <div class="w-full p-6 bg-white rounded-lg" v-if="invoice">
                <div class="border-b border-gray-200 flex flex-col">
                    <img src="https://api.iconify.design/ic:baseline-check-circle.svg?color=%230eb941" width="60" class="m-auto mb-3" alt="Check Icon">
                    <span class="text-center mb-6 font-semibold">Order Successfull!</span>
                </div>
                <div class="py-3 price">
                    <p class="py-3 text-sm flex itemcs-center justify-between"><strong>OrderNumber#</strong> {{ invoice.order_id }}</p>
                    <p class="py-3 text-sm flex itemcs-center justify-between"><strong>Product</strong> {{ invoice.product.name }}</p>
                    <p class="py-3 text-sm flex itemcs-center justify-between"><strong>Payment Method</strong> Stripe Payment</p>
                    <p class="py-3 text-sm flex itemcs-center justify-between"><strong>Status</strong> <span class="text-green-600 font-semibold">Success</span></p>
                    <p class="py-3 text-sm flex itemcs-center justify-between"><strong>Created</strong> {{ invoice.created_at }} (UTC)</p>
                    <p class="py-3 border-t border-gray-200 text-sm flex itemcs-center justify-between"><strong>Total Amount</strong> {{ invoice.product.currency.symbol }}{{ invoice.product.price }}</p>
                </div>
                <p class="text-gray-500 bg-gray-100 rounded-xl p-6 text-sm">Thank you so much for supporting our server. It will help us pay server rent and purchase plugins to make the server more enjoyable</p>
            </div>
            <p class="text-center" v-else>{{ loadedStatus }}</p>
        </div>
    </section>
</template>

<script setup>
    import axios from 'axios';
    axios.defaults.withCredentials = true;

    const loadedStatus = ref("Loading...")

    const config = useRuntimeConfig();
    axios.defaults.baseURL = config.public.url

    const data = useRoute().params;
    const invoice = ref(null);

    definePageMeta({
        layout: 'auth-layout'
    });

    axios.get('/api/success/'+data.slug+'/success')
    .then(result => {
        invoice.value = result.data.data
        if(invoice.value != null){
            loadedStatus.value = "Loaded"
        }else{
            loadedStatus.value = "No such invoice or order exist."
        }
    }).catch(() => {
        loadedStatus.value = "No such invoice or order exist"
        throw createError({ statusCode: 404, statusMessage: 'Order not found!', fatal: true })
    });
</script>