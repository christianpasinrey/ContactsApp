<script setup>
    import { useMediaStack } from '@/stores/mediastack';
    import { useTimelineStore } from '@/stores/timeline';
    import { useUsersStore } from '@/Stores/User';
    import { ref } from 'vue';
    import { format } from 'date-fns';
    import axios from 'axios';

    const timelineStore = useTimelineStore();
    const usersStore = useUsersStore();
    const mediaStack = useMediaStack();

    const sourcesString = ref('');
    const categoriesString = ref('');

    const addTag = (type) => {
        const input = type === 'categories' ? categoriesString : sourcesString;
        const tags = type === 'categories' ? mediaStack.categories : mediaStack.sources;
        const tag = input.value.trim();
        if (tag && !tags.includes(tag)) {
            tags.push(tag);
            input.value = '';
        }
    };

    const removeTag = (type, index) => {
        const tags = type === 'categories' ? mediaStack.categories : mediaStack.sources;
        tags.splice(index, 1);
    };

    const postNew = (news) => {
        //Create the html to post in the body parameter
        const html = `
            <div class="flex flex-col">
                <h3>${news.title}</h3>
                <p>${news.description}</p>
                <a href="${news.url}" target="_blank">Ver noticia</a>
            </div>
            <div class="flex flex-col">
                <img src="${news.image}" alt="${news.title}">
            </div>
            <div class="flex w-full absolute bottom-0 left-0 px-2 justify-end">
                <div class="flex flex-col w-fit">
                    <div class="flex">
                        <span>${news.author}</span>
                        <span>${format(new Date(news.published_at), 'dd/MM/yyyy')}</span>
                    </div>
                </div>
            </div>
        `;
        axios.post(route('posts.store'),{
            body: html,
            user_id: usersStore.authUser.id,
        }).then((response)=>{
            console.log(response.data);
            timelineStore.timeline.data.unshift(response.data);
        }).catch((error)=>{
            console.log(error);
        })
    }
</script>
<template>
    <div class="flex flex-col overflow-y-auto px-12">
        <div class="flex w-full mt-24 mb-2">
            <div class="flex flex-col w-10/12">
                <label for="keywords">Qué noticia buscas?</label>
                <input
                    type="text"
                    v-model="mediaStack.keywords"
                    class="w-full border-2 border-gray-300 bg-white h-10 px-5 pr-16 rounded-l-lg text-sm focus:outline-none"
                    @keyup.enter.prevent="mediaStack.fetchNews"
                    >
            </div>
            <button @click.prevent="mediaStack.fetchNews"
                class="w-fit h-10 py-1 px-2 mt-6 bg-sky-500 hover:bg-sky-600 hover:scale-105 rounded-r-md text-gray-50 font-bold">
                Buscar
            </button>
        </div>
        <div class="flex w-full my-2 gap-3">
            <div class="flex flex-col">
                <label for="categories">Categorías</label>
                <input type="text" id="categories"
                @keyup.space.prevent="addTag('categories')"
                v-model="categoriesString"
                class="w-full border-2 border-gray-300 bg-white h-10 px-5 pr-16 rounded-lg text-sm focus:outline-none">
                <div class="tags-container">
                    <span
                        v-for="(tag, index) in mediaStack.categories"
                        :key="index"
                        class="tag"
                        >
                        {{ tag }}
                        <button class="remove-tag" @click.prevent="removeTag('categories', index)">
                            x
                        </button>
                    </span>
                </div>
            </div>
            <div class="flex flex-col">
                <label for="sources">Fuentes</label>
                <input type="text" id="sources"
                @keyup.space.prevent="addTag('sources')"
                v-model="sourcesString"
                class="w-full border-2 border-gray-300 bg-white h-10 px-5 pr-16 rounded-lg text-sm focus:outline-none">
                <div class="tags-container">
                    <span
                        v-for="(tag, index) in mediaStack.sources"
                        :key="index"
                        class="tag"
                        >
                        {{ tag }}
                        <button class="remove-tag" @click="removeTag('sources', index)">
                            x
                        </button>
                    </span>
                </div>
            </div>
        </div>
        <div class="flex flex-col w-full gap-4" v-if="mediaStack.liveNews.length">
            <div class="flex w-full px-4 pt-4 pb-12 relative bg-slate-200 rounded-md"
                v-for="(news, index) in mediaStack.liveNews"
                :key="`new-${index}`">
                <div class="flex w-full absolute bottom-0 left-0 px-2 justify-between">
                    <div class="flex flex-col w-fit">
                        <button
                            class="w-fit h-fit hover:scale-105 hover:text-sky-600"
                            @click.prevent="postNew(news)"
                            >
                            <svg
                                class="w-6 h-6"
                                viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path fill="#000000" d="M11 7.05V4a1 1 0 0 0-1-1a1 1 0 0 0-.7.29l-7 7a1 1 0 0 0 0 1.42l7 7A1 1 0 0 0 11 18v-3.1h.85a10.89 10.89 0 0 1 8.36 3.72a1 1 0 0 0 1.11.35A1 1 0 0 0 22 18c0-9.12-8.08-10.68-11-10.95zm.85 5.83a14.74 14.74 0 0 0-2 .13A1 1 0 0 0 9 14v1.59L4.42 11L9 6.41V8a1 1 0 0 0 1 1c.91 0 8.11.2 9.67 6.43a13.07 13.07 0 0 0-7.82-2.55z"/>
                            </svg>
                        </button>
                    </div>
                    <div class="flex flex-col w-fit">
                        <div class="flex">
                            <span class="font-bold italic">{{ news.author }} {{ format(new Date(news.published_at), 'dd/MM/yyyy') }}</span>
                            <span> </span>
                        </div>
                    </div>
                </div>
                <div class="flex flex-col w-full">
                    <h3>{{ news.title }}</h3>
                    <p>{{ news.description }}</p>
                    <a :href="news.url" target="_blank">Ver noticia</a>
                </div>
                <div class="flex flex-col">
                    <img :src="news.image" :alt="news.title">
                </div>
            </div>
        </div>
    </div>
</template>
<style scoped>
    .tags-container {
        display: flex;
        flex-wrap: wrap;
        margin-top: 0.5rem;
    }
    .tag {
        background-color: #e2e8f0;
        border-radius: 0.25rem;
        color: #4a5568;
        display: inline-block;
        margin-right: 0.5rem;
        margin-bottom: 0.5rem;
        padding: 0.25rem 0.5rem;
    }
    .remove-tag {
        background-color: transparent;
        border: none;
        color: #4a5568;
        cursor: pointer;
        font-size: 0.75rem;
        margin-left: 0.25rem;
        padding: 0;
    }
</style>
