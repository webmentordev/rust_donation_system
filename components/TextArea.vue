<template>
    <textarea class="w-full bg-white rounded border border-gray-300 focus:border-indigo-500 focus:ring-4 focus:ring-indigo-200 text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out" 
    :value="modelValue"
    @input="$emit('update:modelValue', $event.target.value)" cols="30" rows="4"></textarea>
</template>

<script setup>
    defineProps(['modelValue'])
    defineEmits(['update:modelValue'])
</script>