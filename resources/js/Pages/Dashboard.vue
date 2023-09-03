<script setup>
    import VerticalTimeLineList from '@/Components/Lists/VerticalTimeLineList.vue';
    import VerticalTimelineItem from '@/Components/List-Items/VerticalTimelineItem.vue';
    import TimeLinePostCard from '@/Components/List-Items/Cards/TimeLinePostCard.vue';
    import NewPostCard from '@/Components/List-Items/Cards/NewPostCard.vue';
    import { useUsersStore } from '@/Stores/user';
    import { useTimelineStore } from '@/Stores/timeline';
    import { onBeforeMount,onMounted, onBeforeUnmount } from 'vue';

    const usersStore = useUsersStore();
    const timelineStore = useTimelineStore();

    const handleMainTimelineScroll = () => {
        let element = document.getElementById('timeline-wrapper');
        let scrollPosition = element.scrollTop;
        let scrollHeight = element.scrollHeight;
        let clientHeight = element.clientHeight;

        if(scrollPosition + clientHeight >= scrollHeight){
            timelineStore.loadMoreTimeline();
            element.removeEventListener('scroll',handleMainTimelineScroll);
            setTimeout(()=>{
                element.addEventListener('scroll',handleMainTimelineScroll);
            },1000);
        }
    }

    onBeforeMount(()=>{
        timelineStore.fetchTimeline(usersStore.authUser.id);
    })

    onMounted(()=>{
        let element = document.getElementById('timeline-wrapper');
        element.addEventListener('scroll',handleMainTimelineScroll);
    })

    onBeforeUnmount(()=>{
        let element = document.getElementById('timeline-wrapper');
        if(element){
            element.removeEventListener('scroll',handleMainTimelineScroll);
        }
    })

</script>

<template>
    <div
        id="timeline-wrapper"
        class="overflow-y-auto h-full px-12"
    >
        <h2 class="my-6 text-2xl font-bold text-gray-100">Hola {{ usersStore.authUser?.name }}!</h2>

        <NewPostCard/>
        <VerticalTimeLineList
            :data="timelineStore.timeline?.data"
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
                            :liked="item.liked"
                            :reposted="item.reposted"
                            :repostedCount="item.reposted_count"
                            :comments="item.comments"
                            @repost="timelineStore.repostPost(item.id)"
                            @like="timelineStore.likePost(item.id)"
                            @comment="timelineStore.commentPost(item.id, $event)"
                        />
                    </template>
                </VerticalTimelineItem>
            </template>
        </VerticalTimeLineList>
    </div>
</template>
