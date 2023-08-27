<script setup>
    import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
    import { useUsersStore } from '@/Stores/user';
    import { onBeforeMount } from 'vue';

    const usersStore = useUsersStore();
    const props = defineProps({
        user: {
            type: Object,
            required: false,
        },
    });

    onBeforeMount(() => {
        usersStore.authUser = props.user;
    });

</script>
<template>
    <AuthenticatedLayout>
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            </div>
        </div>
    </AuthenticatedLayout>
</template>
