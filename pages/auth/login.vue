<template>
    <section class="w-full">
        <div class="max-w-2xl w-fit m-auto px-4">
            <div class="w-full p-6 bg-white rounded-lg">
                <h1 class="text-3xl font-semibold mb-6">Login Account</h1>
                <form @submit.prevent="loginHandler" class="flex flex-col">
                    <ErrorMessage v-if="errorMessage" :text="errorMessage" />
                    <div class="w-full mr-2 mb-3">
                        <Label text="Email Address" />
                        <Input type="email" placeholder="Email Address" v-model="email" required />
                        <ErrorMessage v-if="errors" :text="errors.email" />
                    </div>
                    <div class="w-full mb-3">
                        <Label text="Password" />
                        <Input type="password" placeholder="Password" v-model="password" required />
                        <ErrorMessage v-if="errors" :text="errors.password" />
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
    axios.defaults.baseURL = useRuntimeConfig().public.url;
    
    definePageMeta({
        layout: 'auth-layout',
        middleware: 'guest'
    })

    const email = ref("");
    const password = ref("");
    const token = useCookie('token');
    const state = useAuthState();
    const isLoading = ref(false);
    const errors = ref(null);
    const errorMessage = ref(null);

    onMounted(() => {
        if(state.value){
            useRouter().push('/dashboard');
        }
    })

    const loginHandler = () => {
        isLoading.value = true;
        errors.value = null;
        errorMessage.value = null;
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
                isLoading.value = false;
                errors.value = error.response.data.errors;
                errorMessage.value = error.response.data.message;
            });
        })
    }
</script>