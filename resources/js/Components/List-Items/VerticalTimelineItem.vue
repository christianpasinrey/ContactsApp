<script setup>
    import { onMounted,ref } from 'vue';
    const props = defineProps(['item']);

    const post = ref(props.item);

    onMounted(() => {
        console.log(post.value);
    });
</script>
<template>
    <div class="flex flex-row w-full pt-4 justify-start hover:scale-105">
       <slot name="body" :body="post.value?.body"/>
    </div>
</template>
