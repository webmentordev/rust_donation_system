<template>
    <Navbar />
    <section class="w-full h-[83vh] flex items-center justify-center">
        <div class="text-center bg-white p-12 rounded-lg shadow-lg">
            <h1 class="text-8xl font-bold pb-3 text-center text-rust">{{ error.statusCode }}</h1>
            <h2 class="text-2xl font-black mb-3">Oops!</h2>
            <p class="token font-semibold mb-3">{{ error.message }}</p>
            <Button @click="handleClearError" text="Go to Store..." />
        </div>
    </section>
</template>

<script setup>
    defineProps(['error'])
    onUnmounted(() => {
        clearError();
    })
</script>