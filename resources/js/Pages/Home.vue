<script setup>
    import { onMounted } from 'vue';
    import { useRouter } from 'vue-router';

    const vueRouter = useRouter();
    onMounted(()=>{
        vueRouter.push({ name: 'dashboard' });
    })
</script>
<template>

</template>
