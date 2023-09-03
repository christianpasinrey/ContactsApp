<script setup>
    import { computed, ref } from 'vue';
    import { formatDistance } from 'date-fns'
    import { es } from 'date-fns/locale'
    import { useUsersStore } from '@/Stores/user';
    import { useRouter } from 'vue-router';
    import LikeButton from '@/Components/Buttons/LikeButton.vue';
    import RepostButton from '@/Components/Buttons/RepostButton.vue';
    import CommentButton from '@/Components/Buttons/CommentButton.vue';
    import PostCommentsList from '@/Components/Lists/PostCommentsList.vue';

    defineEmits(['like','repost','comment']);

    const usersStore = useUsersStore();
    const vueRouter = useRouter();
    const props = defineProps(['body', 'files','createdAt','comments','mentions','user','liked','reposted','repostedCount']);

    const showingComments = ref(false);

    function toggleShowingComments() {
        showingComments.value = !showingComments.value;
    }

    const handleFilePath = (file) => {
        return `storage/files/${file.path.replace('public/files/', '')}`
    };

    const getDurationOfCreatedPost = computed(() => {
        return formatDistance(new Date(props.createdAt), new Date(), { locale: es });
    });

    async function goToUserProfile(id) {
        try {
            const fetchedUser = await usersStore.fetchUser(id);

            if (fetchedUser) {
                vueRouter.push({ name: 'users.profile', params: { id: id } });
            }
        } catch (error) {
            console.log(error);
        }
    }
</script>
<template>
    <div class="flex flex-row py-1 justify-start px-1 rounded-lg shadow-md bg-slate-200 h-fit w-full hover:bg-slate-300 transition-all duration-500 ease-in-out">
        <div class="timeline-post-body-wrapper">
            <div class="flex flex-row justify-end absolute top-2 left-0 w-full">
                <h3 v-if="user?.id !== usersStore.authUser?.id"
                    class="mx-2 font-medium text-sky-600 cursor-pointer"
                    @click.prevent="goToUserProfile(user?.id)"
                >{{ user?.name }}
            </h3>
            </div>
            <p v-html="body"></p>
            <!-- preview of files -->
            <div class="flex flex-row flex-wrap justify-start gap-2 mt-6 relative">
                <small
                    v-if="props.files.some((f)=>f.type==='image')"
                    class="text-xs font-bold text-gray-500 absolute -bottom-5 left-0"
                >
                    Imágenes adjuntas
                </small>
                <div
                    v-for="file in props.files"
                    :key="`file-${file.id}`"
                    class="rounded-md overflow-hidden shadow-md"
                >
                    <img
                        v-if="file.type === 'image'"
                        class="w-32 h-32 object-cover"
                        :src="handleFilePath(file)"
                        :alt="file.name"
                    />
                </div>
            </div>
            <div class="flex flex-row justify-end relative">
                <div class="flex flex-col w-full">
                    <div class="flex w-full justify-end gap-2 absolute bottom-0">
                        <LikeButton
                            :userLikesPost="props.liked"
                            @like="$emit('like')"
                        />
                        <RepostButton
                            :reposted="props.reposted"
                            :count="props.repostedCount"
                            @repost="$emit('repost')"
                        />
                        <CommentButton
                            @comment="toggleShowingComments"
                        />
                    </div>
                    <p class="flex flex-end text-xs font-bold pt-5 pb-1 text-gray-500">Publicado hace {{ getDurationOfCreatedPost }}</p>
                </div>
            </div>

            <PostCommentsList
                v-if="showingComments"
                :comments="props.comments"
                @commented="$emit('comment', $event)"
            />

        </div>
    </div>
</template>
<style scoped>


</style>
