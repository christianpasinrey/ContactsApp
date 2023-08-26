<script setup>
    const props = defineProps({
        title: {
            type: String,
            default: '',
        },
    });

    const emits = defineEmits(['close']);
</script>
<template>
    <div class="flex flex-row px-2 py-1.5 bg-slate-600 text-gray-100 align-middle justify-between">
        <h3 class="text-lg font-medium leading-6">
            {{ title }}
        </h3>
        <button
            class="hover:scale-110 text-xl"
            @click.prevent="emits('close')">
            &times;
        </button>
    </div>
</template>
