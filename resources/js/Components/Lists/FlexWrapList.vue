<script setup>
    const props = defineProps(['data']);
</script>
<template>
    <div class="flex flex-row flex-wrap justify-center relative gap-3">
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
