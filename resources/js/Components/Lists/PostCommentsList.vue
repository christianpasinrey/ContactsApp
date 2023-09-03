<script setup>
    import { ref } from 'vue';
    import {format} from 'date-fns';
    defineProps(['comments']);
    defineEmits(['commented']);

    const newComment = ref('');
</script>
<template>
    <section name="new-comment">
        <div class="flex flex-col w-full">
            <form @submit.prevent="$emit('commented', newComment)">
                <input type="text"
                v-model="newComment"
                placeholder="Escribe un comentario..."
                class="w-full border-2 border-gray-300 rounded-lg p-2 focus:outline-none focus:border-sky-500 transition-all duration-500 ease-in-out">
                <button type="submit"
                    class="w-full bg-sky-500 text-gray-100 font-semibold rounded-lg p-2 mt-2 hover:bg-sky-600 transition-all duration-500 ease-in-out"
                >
                    Enviar comentario
                </button>
            </form>
        </div>
    </section>
    <section name="comment-list">
        <div class="flex flex-row items-center justify-between">
            <h3 class="text-xl font-bold text-gray-100">Comentarios</h3>
            <span class="text-gray-400 text-sm font-semibold">({{ comments.length }})</span>
        </div>
        <div class="flex flex-col w-full">
            <ol>
                <li v-for="comment in comments" class="relative bg-slate-200 rounded-md px-2">
                    <div class="flex flex-row items-center justify-start gap-4">
                        <div class="flex flex-row items-center align-middle content-center justify-start">
                            <h4 class="text-gray-500 font-bold text-sm ml-2">{{ comment.user.name }}</h4>
                        </div>
                    </div>
                    <div class="flex px-4 py-2">
                        <p class="text-gray-800">{{ comment.body }}</p>
                    </div>
                    <span class="absolute bottom-0 lef-0 w-full flex p-1 justify-end text-gray-400 text-sm font-semibold">{{ format(new Date(comment.created_at),'dd/MM/yyyy') }}</span>
                </li>
            </ol>
        </div>
    </section>
</template>
<style scoped>
    ol{
        list-style: none;
        padding: 0;
        margin:0;
    }
    li{
        margin: 0;
        padding: 0;
    }
</style>
