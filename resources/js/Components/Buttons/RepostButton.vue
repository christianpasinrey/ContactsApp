<script setup>
    defineEmits(['repost']);
    defineProps({
        reposted: {
            type: Boolean,
            required: true
        },
        count: {
            type: Number,
            required: false,
            default: 0
        }
    })
</script>
<template>
    <button
        @click.prevent="$emit('repost')"
        class="w-fit h-fit bg-transparent rounded-full p-1 hover:ring-1 hover:ring-slate-400 transition-all duration-500 ease-in text-gray-800"
        :class="{
            'text-green-500' : reposted,
            'text-gray-800 hover:text-blue-800' : !reposted
        }"
        >
        <svg
            class="h-7 w-7"
            viewBox="0 0 20 20"
            xmlns="http://www.w3.org/2000/svg">
            <path fill="#000000" d="M5 4a2 2 0 0 0-2 2v6H0l4 4l4-4H5V6h7l2-2H5zm10 4h-3l4-4l4 4h-3v6a2 2 0 0 1-2 2H6l2-2h7V8z"/>
        </svg>
    </button>
</template>
