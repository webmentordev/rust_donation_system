<template>
    <Active @click="activeHandler" v-if="server.is_active == 1" />
    <Deactive @click="activeHandler" v-if="server.is_active == 0"/>
    <NuxtImg :src="server.image" width="150px" height="150px" quality="20" class="rounded-lg" />
    <div class="ml-6">
        <div class="flex items-center mb-1 border-b py-3 border-white/20">
            <span class="text-white/60 mr-2">Server</span>
            <h3 class="font-semibold text-white">{{ server.name }}</h3>
            <NuxtLink :to='"/store/"+server.slug' class="bg-rust px-2 ml-3 font-semibold rounded-lg text-sm py-[2px]">Visit</NuxtLink>
        </div>
        <div class="flex text-white flex-col">
            <div class="flex items-center">
                <span class="text-white/60">Token —</span>
                <p class="p-2 rounded-lg bg-dark text-white token">{{ server.token }}</p>
            </div>
            <div class="flex items-center">
                <span class="text-white/60">Packages —</span>
                <p class="p-2 rounded-lg bg-dark text-rust">{{ server.products.length }}</p>
            </div>
            <div class="flex items-center">
                <span class="text-white/60">Created —</span>
                <p class="p-2 rounded-lg bg-dark text-rust">{{ server.created }}</p>
            </div>
        </div>
        <Success v-if="isSuccess" text="Server status has changed!" />
        <Loading v-if="isLoading" text="Updating data..." />
    </div>
</template>

<script setup>
    import axios from 'axios';
    axios.defaults.withCredentials = true;

    const { server } = defineProps(['server'])
    const emit = defineEmits(['clickEvent'])

    const config = useRuntimeConfig();
    axios.defaults.baseURL = config.public.url

    const isLoading = ref(false);
    const isSuccess = ref(false);

    const activeHandler = () => {
        isLoading.value = true;
        isSuccess.value = false;
        axios.patch(`/api/server/update/status/${server.id}`)
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