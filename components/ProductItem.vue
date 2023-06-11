<template>
    <Active @click="activeHandler" v-if="product.is_active == 1" />
    <Deactive @click="activeHandler" v-if="product.is_active == 0"/>
    <NuxtImg :src="product.image" width="150px" quality="20" class="rounded-lg h-fit" />
    <div class="ml-6">
        <div class="flex items-center mb-1 border-b py-3 border-white/20">
            <span class="text-white/60 mr-2">Package</span>
            <h3 class="font-semibold text-white">{{ product.name }}</h3>
            <NuxtLink :to='"/store/"+product.slug' class="bg-rust px-2 ml-3 font-semibold rounded-lg text-sm py-[2px]">Visit</NuxtLink>
        </div>
        <div class="flex text-white flex-col">
            <div class="flex items-center">
                <span class="text-white/60">Stripe —</span>
                <p class="p-2 rounded-lg bg-dark text-white token">{{ product.product_id }}</p>
            </div>
            <div class="flex items-center">
                <span class="text-white/60">Server —</span>
                <p class="p-2 rounded-lg bg-dark text-white token">{{ product.server.name }}</p>
            </div>
            <div class="flex items-center">
                <span class="text-white/60">Created —</span>
                <p class="text-rust p-2">{{ product.created }}</p>
            </div>
        </div>
        <div class="absolute right-4 bottom-3">
            <Button @click="status = !status" text="Read" />
            <span v-if="status" class="w-[400px] fixed bg-white p-6 rounded-lg shadow-lg bottom-5 right-5" v-html="product.description"></span>
        </div>
        <span class="price text-white absolute left-3 bottom-3">{{ product.price }} {{ product.currency }}</span>
        <Success v-if="isSuccess" text="Server status has changed!" />
        <Loading v-if="isLoading" text="Updating data..." />
    </div>
</template>

<script setup>
    import axios from 'axios';
    axios.defaults.withCredentials = true;

    const { product } = defineProps(['product'])
    const emit = defineEmits(['clickEvent'])

    const config = useRuntimeConfig();
    axios.defaults.baseURL = config.public.url

    const status = ref(false)
    const isLoading = ref(false);
    const isSuccess = ref(false);

    const activeHandler = () => {
        isLoading.value = true;
        isSuccess.value = false;
        axios.patch(`/api/product/update/status/${product.id}`)
        .then(result => {
            if(result.status == 200){
                isSuccess.value = true;
                isLoading.value = false;
                emit('clickEvent');
            }
        }).catch(() => {
            isLoading.value = false;
        });
    }
</script>