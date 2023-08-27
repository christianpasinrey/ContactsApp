<script setup>
    import { set } from '@vueuse/core';
import { ref } from 'vue';

    import SocialMediaIcons from '../../SocialIcons.vue';

    const emits = defineEmits(['edit','delete']);
    const props = defineProps(['contact'])

    const isHovered = ref(false);

</script>
<template>
    <div class="user-profile-contact-card transition-all duration-500 ease-in-out"
        @mouseover="isHovered = true"
        @mouseleave="isHovered = false"
    >
        <div class="flex flex-row justify-between">
            <span
                id="contact-name"
                class="font-medium text-xs uppercase"
                >{{ contact.name }}
            </span>
        </div>
        <div class="flex flex-row justify-center transition-all duration-500 ease-in-out"
            :class="{
                'h-2' : !isHovered,
                'h-6' : isHovered,
            }"
        >
            <div class="flex flex-col w-fit pt-2"
                :class="{
                    'hidden' : !isHovered,
                    'block' : isHovered
                }">
                <SocialMediaIcons
                    :contact="contact"></SocialMediaIcons>
            </div>
        </div>
    </div>
</template>
