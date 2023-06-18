<template>
    <section class="w-full">
        <div class="flex justify-between items-center pb-3 border-b border-gray-200">
            <h1 class="text-3xl font-semibold">Coupons List</h1>
            <Button @click="pop = !pop" text="Create"/>
        </div>
        <div v-if="pop" @click.self="pop = !pop" class="fixed bg-black bg-opacity-75 backdrop-blur-md w-full h-full flex items-center justify-center top-0 left-0">
            <form @submit.prevent="uploadHandler" enctype="multipart/form-data" class="flex flex-col max-w-xl w-full items-center p-12 bg-white rounded-lg">
                <h2 class="font-semibold text-lg mb-3">Create Coupon / PROMO Codes</h2>
                <div class="w-full">
                    <Success v-if="isSuccess" text="Currency has been created!" />
                    <Loading v-if="isLoading" text="Posting data..." />
                    <div class="w-full mb-3">
                        <Label text="Coupon Code" />
                        <Input placeholder="Coupon Code" v-model="code" required />
                    </div>
                    <div class="w-full mb-3">
                        <Label text="Percentage Off (100% is FREE)" />
                        <Input type="number" placeholder="% Off" v-model="percent" step="0.01" required />
                    </div>
                    <div class="w-full mb-3">
                        <Label text="Currency" />
                        <CurrencyItem v-model="currency" required />
                    </div>
                    <div class="w-full mb-3">
                        <Label text="Max Redemption (leave empty for infinite)" />
                        <Input type="number" placeholder="Max Redemption" v-model="max" />
                    </div>
                    <Button type="submit" class="w-full" text="Create"/>
                </div>
            </form>
        </div>
        <div v-if="coupons.length" class="rounded-lg mb-3 overflow-hidden">
            <table class="w-full">
                <tr class="bg-black text-white">
                    <th class="p-2 text-start">ID #</th>
                    <th class="text-start">Code</th>
                    <th class="text-start">PercentOFF</th>
                    <th class="text-start">CouponName</th>
                    <th class="p-2 text-end">Created</th>
                </tr>
                <tr v-for="(coupon, index) in coupons" :key="index" class="odd:bg-gray-100">
                    <td class="p-2 text-start">{{ coupon.id }}</td>
                    <td class="text-start">{{ coupon.code }}</td>
                    <td class="text-start">{{ coupon.percent }}</td>
                    <td class="text-start">{{ coupon.coupon_name }}</td>
                    <td class="p-2 text-end">{{ coupon.created }}</td>
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

    const max = ref(0);
    const code = ref("");
    const currency = ref("");
    const percent = ref();
    const pop = ref(false);
    const coupons = ref([]);
    const isLoading = ref(false);
    const isSuccess = ref(false);
    const loadedStatus = ref("Loading...")

    onMounted(() => {
        fetchCoupon();
    })

    function fetchCoupon(){
        axios.get('/api/coupons')
        .then(result => {
            coupons.value = result.data.data
            if(coupons.value.length){
                loadedStatus.value = "Loaded"
            }else{
                loadedStatus.value = "No coupon data exist."
            }
        });
    }

    function uploadHandler(){
        isLoading.value = true;
        isSuccess.value = false;
        const formData = {
            code: code.value,
            max: max.value,
            currency: currency.value,
            percent: percent.value,
        };
        axios.get('/sanctum/csrf-cookie')
        .then(() => {
            axios.post('/api/coupons', formData).then(() => {
                isSuccess.value = true;
                isLoading.value = false;
                fetchCoupon();
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