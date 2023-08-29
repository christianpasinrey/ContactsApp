<script setup>
    const props = defineProps(['data']);
</script>
<template>
    <div class="flex flex-row flex-wrap justify-center overflow-auto px-4 py-4 relative">
        <div v-for="item in data"
            :key="`item-${item.id}`"
        >
            <slot
                :item="item"
                name="item"
            ></slot>
        </div>
    </div>
</template>
