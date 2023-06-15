<template>
    <QuillEditor :options="options" contentType="html" />
</template>

<script setup>
    import { QuillEditor } from '@vueup/vue-quill'
    import '@vueup/vue-quill/dist/vue-quill.snow.css';

    var toolbarOptions = [
        ['bold', 'italic', 'underline', 'strike'],
        ['blockquote', 'code-block'],
        [{ 'list': 'ordered'}, { 'list': 'bullet' }],
        [{ 'script': 'sub'}, { 'script': 'super' }],
        [{ 'indent': '-1'}, { 'indent': '+1' }],
        [{ 'size': ['small', false, 'large', 'huge'] }],
        [{ 'header': [1, 2, 3, 4, 5, 6, false] }],
        [{ 'color': [] }, { 'background': [] }],      
        [{ 'font': [] }],
        ['clean']
    ];
    const options = ref({
        modules: {
            toolbar: toolbarOptions
        },
        placeholder: 'Compose here...',
        readOnly: false,
        theme: 'snow'
    });
    defineComponent({
        name: QuillEditor
    })
</script>