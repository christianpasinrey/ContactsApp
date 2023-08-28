<script setup>
    import VerticalTimeLineItem from '../List-Items/VerticalTimelineItem.vue';
    import {onMounted } from 'vue';
    const props = defineProps({
        data: {
            type: Array,
            required: false,
            default: () => [],
        },
    });

    onMounted(() => {
        console.log(props.data);
    });
</script>
<template>
    <div
        class="w-full grid grid-cols-12 pt-4 justify-start px-5 py-4 relative">
        <div
            class="flex col-span-12 justify-center items-center"
            v-for="item in data"
            :key="`item-${item.id}`"
        >
            <slot name="item" :item="item"></slot>
        </div>
    </div>
</template>
