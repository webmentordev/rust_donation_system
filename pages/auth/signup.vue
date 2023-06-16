<template>
    <section class="w-full">
        <div class="max-w-2xl w-fit m-auto px-4">
            <div class="w-full p-6 bg-white rounded-lg">
                <h1 class="text-3xl font-semibold mb-6">Create Account</h1>
                <form @submit.prevent="signupHandler" class="flex flex-col mb-3">
                <div class="w-full mr-2 mb-3">
                    <Label text="Username" />
                    <Input placeholder="Username" v-model="name" required />
                </div>
                <div class="w-full mr-2 mb-3">
                    <Label text="Email Address" />
                    <Input type="email" placeholder="Email Address" v-model="email" required />
                </div>
                <div class="w-full mr-2 mb-3">
                    <Label text="Steam 64_ID" />
                    <Input placeholder="Steam 64_ID" v-model="steam_id" required />
                </div>
                <div class="w-full mb-3">
                    <Label text="Password" />
                    <Input type="password" placeholder="Password" v-model="password" required />
                </div>
                <div class="w-full mb-3">
                    <Label text="Confirm Password" />
                    <Input type="password" placeholder="Confirm Password" v-model="c_password" required />
                </div>
                <div class="w-full">
                    <Button type="submit" text="Register"/>
                </div>
            </form>
            <p class="text-sm text-gray-500 text-center">Don't know your SteamID? Search <NuxtLink to="https://www.steamidfinder.com/" target="_blank" class="underline text-rust">here</NuxtLink> by username</p>
            <Success v-if="isSuccess" text="Account has been created!" />
            <Loading v-if="isLoading" text="Signing Up..." />
            </div>
        </div>
    </section>
</template>

<script setup>
    import axios from 'axios';
    axios.defaults.withCredentials = true;

    const config = useRuntimeConfig();
    axios.defaults.baseURL = config.public.url;
    

    definePageMeta({
        layout: 'auth-layout',
        middleware: 'guest'
    })

    const name = ref("");
    const email = ref("");
    const password = ref("");
    const c_password = ref("");
    const steam_id = ref("");

    const isLoading = ref(false);
    const isSuccess = ref(false);

    const state = useAuthState();
    onMounted(() => {
        if(state.value){
            useRouter().push('/dashboard');
        }
    })

    const signupHandler = () => {
        isLoading.value = true;
        isSuccess.value = false;
        const formData = {
            email: email.value,
            name: name.value,
            steam_id: steam_id.value,
            password: password.value,
            password_confirmation: c_password.value,
        }
        axios.get('/sanctum/csrf-cookie')
        .then(() => {
            axios.post('/api/register', formData, {
            }).then(() => {
                isLoading.value = false;
                isSuccess.value = true;
                setTimeout(() => {
                    isSuccess.value = false;
                }, 3000);
            }).catch((error) => {
                isLoading.value = false;
                console.log(error);
            });
        }).catch((error) => {
            isLoading.value = false;
            console.log(error.response.data.message)
        });
    }
</script>