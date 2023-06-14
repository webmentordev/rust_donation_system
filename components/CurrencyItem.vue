<template>
    <select class="w-full bg-white rounded border border-gray-300 focus:border-indigo-500 focus:ring-4 focus:ring-indigo-200 text-base outline-none text-gray-700 py-[9px] px-3 leading-8 transition-colors duration-200 ease-in-out" 
    :value="modelValue"
    @input="$emit('update:modelValue', $event.target.value)">
        <option value="" selected>—Select Currency—</option>
        <option v-if="currencies.length" v-for="(currency, index) in currencies" :key="index" :value="currency.id">{{ currency.code }}{{ currency.symbol }}</option>
        <option v-else value="">Please add currency</option>
    </select>
</template>

<script setup>
    import axios from 'axios';
    axios.defaults.withCredentials = true;
    defineProps(['modelValue'])
    defineEmits(['update:modelValue'])

    const currencies = ref([]);
    const config = useRuntimeConfig();
    axios.defaults.baseURL = config.public.url

    onMounted(() => {
        axios.get('/api/currencies')
        .then(result => {
            currencies.value = result.data.data
        });
    })
</script>