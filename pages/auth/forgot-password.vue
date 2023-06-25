<template>
    <section class="w-full">
        <div class="max-w-[400px] w-fit m-auto px-4">
            <div class="w-full p-1 bg-white rounded-lg">
                <h1 class="text-3xl font-semibold mb-3">Forgot Password</h1>
                <form @submit.prevent="forgotHandler" class="flex flex-col">
                    <ErrorMessage v-if="errorMessage" :text="errorMessage" />
                    <p class="text-gray-600 mb-3">We'll send an email with password reset link. Click that link and change the password</p>
                    <div class="w-full mr-2 mb-3">
                        <Label text="Email Address" />
                        <Input type="email" placeholder="Email Address" v-model="email" required />
                        <ErrorMessage v-if="errors" :text="errors.email" />
                    </div>
                    <div class="w-full mb-3">
                        <Button type="submit" text="Forgot Password"/>
                    </div>
                </form>
                <Loading v-if="isLoading" text="Sending email..." />
                <Success v-if="isSuccess" text="Email has been sent!" />
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
    const isLoading = ref(false);
    const isSuccess = ref(false);
    const errors = ref(null);
    const errorMessage = ref(null);

    const forgotHandler = () => {
        isLoading.value = true;
        isSuccess.value = false;
        errors.value = null;
        errorMessage.value = null;
        const formData = {
            email: email.value
        }
        axios.get('/sanctum/csrf-cookie')
        .then(() => {
            axios.post('/api/forgot-password', formData, {
            }).then(() => {
                isLoading.value = false;
                isSuccess.value = true;
                setTimeout(() => {
                    isSuccess.value = false;
                }, 3000);
            }).catch((error) => {
                isLoading.value = false;
                errors.value = error.response.data.errors;
                errorMessage.value = error.response.data.message;
            });
        });
    }
</script>