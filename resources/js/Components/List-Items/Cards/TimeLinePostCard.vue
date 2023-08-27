<script setup>
    import { onMounted } from 'vue';
    const props = defineProps(['body']);

    onMounted(() => {
        console.log(props.body);
    });
</script>
<template>
    <div class="flex flex-row py-1 justify-start px-1 rounded-lg shadow-md bg-slate-300 h-fit w-full hover:bg-slate-400 transition-all duration-500 ease-in-out">
        <div class="timeline-post-body-wrapper">
            {{ body }}
        </div>
    </div>
</template>
