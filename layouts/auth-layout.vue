<template>
    <NuxtLoadingIndicator />
    <Navbar />
    <div class="bg-cover bg-center relative" style="background-image: url('/auth-header.jpg');">
        <div class="bg-black bg-opacity-20 backdrop-blur-sm absolute w-full h-full top-0 left-0"></div>
        <div class="flex items-center w-fit m-auto py-12 px-12 min-h-[95vh] h-full relative">
        <div class="bg-white p-8 h-fit rounded-lg shadow-sm border border-gray-200 ml-6 w-full">
            <slot />
        </div>
    </div>
    </div>
    <Footer />
</template>