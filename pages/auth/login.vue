<template>
    <section class="w-full">
        <div class="max-w-2xl w-fit m-auto px-4">
            <div class="w-full p-6 bg-white rounded-lg">
                <h1 class="text-3xl font-semibold mb-6">Login Account</h1>
                <form @submit.prevent="loginHandler" class="flex flex-col">
                <div class="w-full mr-2 mb-3">
                    <Label text="Email Address" />
                    <Input type="email" placeholder="Email Address" v-model="email" required />
                </div>
                <div class="w-full mb-3">
                    <Label text="Password" />
                    <Input type="password" placeholder="Password" v-model="password" required />
                </div>
                <div class="w-full">
                    <Button type="submit" text="Login Now"/>
                </div>
            </form>
            <Loading v-if="isLoading" text="Logging In..." />
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
        layout: 'custom',
        middleware: 'guest'
    })

    const email = ref("");
    const password = ref("");

    const token = useCookie('token');
    const state = useAuthState();

    const isLoading = ref(false);

    onMounted(() => {
        if(state.value){
            useRouter().push('/dashboard');
        }
    })

    const loginHandler = () => {
        isLoading.value = true;
        const formData = {
            email: email.value,
            password: password.value
        }
        axios.get('/sanctum/csrf-cookie')
        .then(() => {
            axios.post('/api/login', formData, {
            }).then((result) => {
                token.value = result.data.token;
                state.value = result.data.token;
                isLoading.value = false;
                useRouter().push('/dashboard');
            }).catch((error) => {
                console.log(error);
            });
        }).catch((error) => {
            console.log(error.response.data.message)
        });
    }
</script>