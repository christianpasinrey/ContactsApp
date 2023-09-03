<script setup>
    import { ref, computed } from 'vue';
    defineEmits(['like']);
    const props = defineProps({
        userLikesPost: {
            type: Boolean,
            required: true
        }
    })
    const isLiked = ref(null);
    const isNotLiked = ref(null);

    const handleLikeButton = computed(()=>{
        return props.userLikesPost ?
            isNotLiked.value.classList.add('hidden') :
            isLiked.value.classList.add('hidden');
    })

    const handleMouseEnter = (event) => {
        if(!props.userLikesPost){
            isLiked.value.classList.remove('hidden');
            isNotLiked.value.classList.add('hidden');
        }else{
            isLiked.value.classList.add('hidden');
            isNotLiked.value.classList.remove('hidden');
        }
    }

    const handleMouseLeave = (event) => {
        if(!props.userLikesPost){
            isLiked.value.classList.add('hidden');
            isNotLiked.value.classList.remove('hidden');
        }else{
            isLiked.value.classList.remove('hidden');
            isNotLiked.value.classList.add('hidden');
        }
    }
</script>
<template>
    <div
        @mouseenter="handleMouseEnter($event)"
        @mouseleave="handleMouseLeave($event)"
    >
        <button
            @click.prevent="$emit('like')"
            class="w-fit h-fit bg-transparent rounded-full p-1 hover:ring-1 hover:ring-slate-400 transition-all duration-500 ease-in"
            >
            <svg
                ref="isLiked"
                class="w-6 h-6 hidden"
                viewBox="0 0 48 48"
                xmlns="http://www.w3.org/2000/svg">
                <path fill="red" d="M34 9c-4.2 0-7.9 2.1-10 5.4C21.9 11.1 18.2 9 14 9C7.4 9 2 14.4 2 21c0 11.9 22 24 22 24s22-12 22-24c0-6.6-5.4-12-12-12z"/>
            </svg>
            <svg
                ref="isNotLiked"
                class="w-6 h-6"
                viewBox="0 0 48 48" xmlns="http://www.w3.org/2000/svg">
                <path fill="none" stroke="#000000" stroke-linecap="round" stroke-linejoin="round" stroke-width="4" d="M15 8C8.925 8 4 12.925 4 19c0 11 13 21 20 23.326C31 40 44 30 44 19c0-6.075-4.925-11-11-11c-3.72 0-7.01 1.847-9 4.674A10.987 10.987 0 0 0 15 8Z"/>
            </svg>
        </button>
    </div>
</template>

