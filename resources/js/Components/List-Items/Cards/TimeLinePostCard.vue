<script setup>
    import { computed } from 'vue';
    //import utilities from date-fns to calculate durations
    import { formatDistance } from 'date-fns'
    import { es } from 'date-fns/locale'
    import { useUsersStore } from '@/Stores/user';
    import { useRouter } from 'vue-router';

    const usersStore = useUsersStore();
    const vueRouter = useRouter();
    const props = defineProps(['body', 'files','createdAt','mentions','user']);
    const handleFilePath = (file) => {
        return `storage/files/${file.path.replace('public/files/', '')}`
    };

    const getDurationOfCreatedPost = computed(() => {
        //return time passed from now to created_at using date-fns in spanish
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
            <div class="flex flex-row justify-end">
                <p class="flex flex-end text-xs font-bold pt-5 pb-1 text-gray-500">Publicado hace {{ getDurationOfCreatedPost }}</p>
            </div>
        </div>
    </div>
</template>
