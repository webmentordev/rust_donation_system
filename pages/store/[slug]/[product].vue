<template>
    <section class="w-full py-6">
        <div class="max-w-[450px] w-full rounded-lg ml-6">
            <h1 class="font-bold text-3xl mb-3">Final step, make the payment</h1>
            <div class="bg-gray-100 p-8 py-12  rounded-lg w-full mb-3">
                <h3 class="text-slate-500/80 font-semibold text-[15px] mb-3">You've to pay</h3>
                <span class="price font-semibold text-5xl mb-3">$235.50</span>
                <div class="flex mt-3 mb-1">
                    <img src="https://api.iconify.design/ic:round-check-circle.svg?color=%2321b039" width="20" alt="Check">
                    <span class="font-semibold ml-2">How to claim?</span>
                </div>
                <p class="text-[14px] text-slate-500">After completing the payment, join the server and type <span class="p-1 px-3 bg-gray-200 rounded-lg inline-block">/claim-donation</span> in-game chat. Enjoy your perks & rank.</p>
                <div class="flex mt-3 mb-2">
                    <img src="https://api.iconify.design/ic:round-check-circle.svg?color=%2321b039" width="20" alt="Check">
                    <span class="font-semibold ml-2">Payment & Invoices</span>
                </div>
                <p class="text-[14px] text-slate-500">We'll worry about all the transctions and payment. You can sit back and relax while you enjoy your wipe.</p>

                <div class="flex mt-3 mb-2">
                    <img src="https://api.iconify.design/ic:round-check-circle.svg?color=%2321b039" width="20" alt="Check">
                    <span class="font-semibold ml-2">Discounts & Offers</span>
                </div>
                <p class="text-[14px] text-slate-500">You'll be provided with the offers from time to time and also have perks and benefits.</p>
            </div>
            <Button @click="" text="Checkout" />
            <p class="text-center mt-6 flex items-center justify-center text-gray-500">Powered by <NuxtImg class="ml-2" src="https://api.iconify.design/logos:stripe.svg?color=%23f33f12" width="50px" /></p>
        </div>
    </section>
</template>

<script setup>
    const data = useRoute();
    definePageMeta({
        layout: 'auth-layout'
    })
</script>