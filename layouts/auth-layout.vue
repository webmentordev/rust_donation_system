<template>
    <NuxtLoadingIndicator />
    <Navbar />
    <div class="flex w-fit m-auto py-12 px-12 min-h-[85vh] h-full">
        <div class="bg-white p-8 h-fit rounded-lg shadow-sm border border-gray-200 ml-6 w-full">
            <slot />
        </div>
    </div>
    <Footer />
</template>