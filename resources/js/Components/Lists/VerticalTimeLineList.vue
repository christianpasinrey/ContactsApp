<script setup>
    import { onMounted, computed } from 'vue';
    import { useRouter } from 'vue-router';
    import {useUsersStore} from '@/stores/user';

    const usersStore = useUsersStore();
    const vueRouter = useRouter();

    const props = defineProps({
        data: {
            type: Object,
            required: false,
            default: () => [],
        },
    });

    const orderedData = computed(()=>{
        //convert data to array, order it, and return in a object again
        return Object.values(props.data).sort((a,b)=>b.id-a.id);
    })

    const goToUserProfile = () => {
        vueRouter.push({ name: 'users.profile', params: { id: props.user.id } });
    };

    onMounted(()=>{
        //get all elements that includes 'user-mentioned' in class
        let mentionLinks = document.querySelectorAll('.user-mentioned');
        mentionLinks?.forEach((link)=>{
            link.addEventListener('click',(e)=>{
                e.preventDefault();
                usersStore.fetchUser(e.target.id);
                let id = e.target.id;
                setTimeout(()=>{
                    vueRouter.push(
                        { name: 'users.profile', params: { id: id }
                    });
                },200)
            })
        })
    })
</script>
<template>
    <div
        class="w-full grid grid-cols-12 gap-6 justify-start px-5 py-4 relative">
        <div
            class="flex col-span-12 justify-center items-center"
            v-for="item in orderedData"
            :key="`item-${item.id}`"
        >
            <slot name="item" :item="item"></slot>
        </div>
    </div>
</template>
