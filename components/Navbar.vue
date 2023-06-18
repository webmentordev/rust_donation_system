<template>
    <nav class="w-full sticky top-0 left-0 z-50 bg-white border-b border-gray-200">
        <div class="max-w-6xl m-auto py-3 px-4 flex items-center justify-between">
            <nuxt-link to="/"><nuxt-img src="https://files.facepunch.com/lewis/1b2911b1/rust-marque.svg" width="35"></nuxt-img></nuxt-link>
            <ul class="flex items-center font-semibold">
                <nuxt-link class="ml-8" to="/">Home</nuxt-link>
                <nuxt-link class="ml-8" to="/store">Store</nuxt-link>
                <nuxt-link v-if="auth" class="ml-8" to="/dashboard">Dashboard</nuxt-link>
                <button v-if="auth" @click="logoutHandler" class="ml-8">Logout</button>
                <nuxt-link v-if="!auth" class="ml-8" to="/auth/login">Login</nuxt-link>
                <nuxt-link v-if="!auth" class="ml-8" to="/auth/signup">Signup</nuxt-link>
            </ul>
            <Loading v-if="isLoading" text="Logging Out..." />
        </div>
    </nav>
</template>

<script setup>
    import axios from 'axios';
    axios.defaults.withCredentials = true;
    axios.defaults.baseURL = useRuntimeConfig().public.url
    
    const auth = useAuthState();
    const authState = ref(false);
    const isLoading = ref(false);
    
    onMounted(() => {
        const cookie = useCookie('token');
        if(cookie != null){
            auth.value = cookie
            authState.value = true
        }
    });

    const logoutHandler = () => {
        isLoading.value = true;
        const product = { name: 'Logged Out' };
        axios.get('/sanctum/csrf-cookie')
        .then(() => {
            axios.post('/api/logout', product, {
                headers: {
                    'Authorization': 'Bearer ' + auth.value
                }
            }).then(() => {
                auth.value = null;
                authState.value = false;
                isLoading.value = false;
                useRouter().push('/auth/login');
            })
        }).catch((error) => {
            isLoading.value = false;
            console.log(error.response.data.message);
        });
    }
</script>