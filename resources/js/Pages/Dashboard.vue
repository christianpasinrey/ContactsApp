<script setup>
    import VerticalTimeLineList from '@/Components/Lists/VerticalTimeLineList.vue';
    import VerticalTimelineItem from '@/Components/List-Items/VerticalTimelineItem.vue';
    import TimeLinePostCard from '@/Components/List-Items/Cards/TimeLinePostCard.vue';
    import NewPostCard from '@/Components/List-Items/Cards/NewPostCard.vue';
    import { useUsersStore } from '@/Stores/user';
import { onBeforeMount } from 'vue';

    const usersStore = useUsersStore();

    const posts = usersStore.authUser?.timeline;

</script>

<template>
    <div class="w-full flex h-full">
        <div class="flex flex-col justify-evenly shadow-md border-r sm:w-full md:w-3/12 lg:w-2/12 p-8 bg-gradient-to-tr from-gray-50 dark:from-gray-950 via-slate-300 dark:via-slate-800 to-sky-300 dark:to-gray-300 opacity-80 h-[93vh]">
            1
        </div>
        <div
            id="no-scrollbar-element"
            class="flex flex-col w-full md:w-8/12 overflow-y-auto text-start max-h-[93vh]"
        >
            <NewPostCard/>
            <VerticalTimeLineList
                :data="posts"
            >
                <template #item="{ item }">
                    <VerticalTimelineItem
                        :item="item"
                    >
                        <template #body>
                            <TimeLinePostCard
                                :body="item.body"
                                :files="item.files"
                                :createdAt="item.created_at"
                                :mentions="item.mentions"
                                :user="item.user"
                            />
                        </template>
                    </VerticalTimelineItem>
                </template>
            </VerticalTimeLineList>
        </div>
        <div class="flex flex-col flex-wrap text-center overflow-x-hidden justify-evenly sm:w-full md:w-2/12 py-8 bg-gradient-to-br from-sky-300 dark:from-gray-300 via-slate-300 dark:via-slate-800 to-gray-50 dark:to-gray-950 opacity-80 h-[93vh]">
            3
        </div>
    </div>
</template>
