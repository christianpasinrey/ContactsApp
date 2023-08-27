<script setup>
    import VerticalTimeLineList from '@/Components/Lists/VerticalTimeLineList.vue';
    import TimeLinePostCard from '@/Components/List-Items/Cards/TimeLinePostCard.vue';
    import NewPostCard from '@/Components/List-Items/Cards/NewPostCard.vue';
    import { useUsersStore } from '@/Stores/user';
    import { ref } from 'vue';
import VerticalTimelineItem from '@/Components/List-Items/VerticalTimelineItem.vue';

    const usersStore = useUsersStore();

    const posts = usersStore.authUser?.posts;
    const newPost = ref('');
</script>

<template>
    <div class="w-full flex h-full">
        <div class="flex flex-col justify-evenly shadow-md border-r sm:w-full md:w-3/12 lg:w-2/12 p-8 bg-gradient-to-tr from-cyan-950 via-sky-800 to-gray-300 opacity-80 h-[93vh]">
            1
        </div>
        <div class="flex flex-col w-full md:w-8/12">
            <div class="flex flex-row justify-center w-full px-6">
                <NewPostCard/>
            </div>
            <div class="flex flex-row justify-center w-full px-20">
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
                                />
                            </template>
                        </VerticalTimelineItem>
                    </template>
                </VerticalTimeLineList>
            </div>
        </div>
        <div class="flex flex-col flex-wrap text-center overflow-x-hidden justify-evenly sm:w-full md:w-2/12 py-8 bg-gradient-to-br from-gray-300 via-sky-800 to-cyan-950 opacity-80 h-[93vh]">
            3
        </div>
    </div>

</template>
