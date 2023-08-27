<script setup>
    import { ref } from 'vue';
    import { useUsersStore } from '@/Stores/user';

    const usersStore = useUsersStore();

    const newPost = ref('');
</script>
<template>
    <div class="new-post-card">
        <div class="flex flex-row justify-between items-center">
            <div class="flex flex-row items-center">
<!--                                 <img src="storage/user-avatar.png" class="w-10 h-10 rounded-full" alt="user-avatar">
-->                                <span class="text-gray-100 font-bold ml-2">{{ usersStore.authUser?.name }}</span>
            </div>

        </div>
        <textarea
            id="new-post-textarea"
            class="w-full h-24 mt-2 bg-sky-200 focus:outline-none focus:ring-2 focus:ring-sky-600 focus:border-transparent rounded-md"
            placeholder="¿Qué estás pensando?"
            v-model="newPost"
        ></textarea>
        <button
            :disabled="newPost.length === 0"
            @click.prevent="usersStore.createPost(newPost)"
            class="bg-sky-500 hover:bg-sky-700 text-white font-bold py-2 px-4 rounded-md">
            Publicar
        </button>
    </div>
</template>
