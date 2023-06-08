<template>
    <nav class="w-full sticky top-0 left-0 z-50 bg-white border-b border-gray-200">
        <div class="max-w-6xl m-auto py-6 px-4 flex items-center justify-between">
            <nuxt-link to="/">Home</nuxt-link>
            <ul class="flex items-center font-semibold">
                <nuxt-link class="ml-8" to="/">Home</nuxt-link>
                <nuxt-link class="ml-8" to="/store">Store</nuxt-link>
                <nuxt-link class="ml-8" to="/dashboard">Dashboard</nuxt-link>
            </ul>
        </div>
    </nav>
</template>