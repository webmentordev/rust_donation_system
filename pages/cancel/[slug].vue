<template>
    <section class="w-full">
        <div class="max-w-lg w-fit m-auto">
            <div class="w-full p-6 bg-white rounded-lg" v-if="invoice">
                <div class="border-b border-gray-200 flex flex-col">
                    <img src="https://api.iconify.design/gridicons:cross-circle.svg?color=%23d83013" width="60" class="m-auto mb-3" alt="Times Icon">
                    <span class="text-center mb-6 font-semibold">Order has been cancelled!</span>
                </div>
                <div class="py-3">
                    <p class="py-3 text-sm flex itemcs-center justify-between"><strong>OrderNumber#</strong> {{ invoice.order_id }}</p>
                    <p class="py-3 text-sm flex itemcs-center justify-between"><strong>Product</strong> {{ invoice.product.name }}</p>
                    <p class="py-3 text-sm flex itemcs-center justify-between"><strong>Payment Method</strong> Stripe Payment</p>
                    <p class="py-3 text-sm flex itemcs-center justify-between"><strong>Status</strong> <span class="text-red-600 font-semibold">Cancelled</span></p>
                    <p class="py-3 text-sm flex itemcs-center justify-between"><strong>Created</strong> {{ invoice.created_at }} (UTC)</p>
                    <p class="py-3 border-t border-gray-200 text-sm flex itemcs-center justify-between"><strong>Total Amount</strong> {{ invoice.product.currency.symbol }}{{ invoice.product.price }}</p>
                </div>
                <p class="text-gray-500 bg-gray-100 rounded-xl p-6 text-sm">We're sorry to hear that you won't be proceeding with the purchase, we fully respect your decision and understand that circumstances can change.</p>
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

    const token = useAuthState();

    definePageMeta({
        layout: 'auth-layout',
        middleware: 'auth'
    });

    onMounted(() => {
        if(!token.value){
            useRouter().push('/auth/login');
        }
        axios.get('/sanctum/csrf-cookie').then(() => {
            axios.post('/api/cancel/'+data.slug, { message: 'cancel check' }, { headers: { 'Authorization': 'Bearer ' + token.value } })
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
        })
    })
</script>