<script setup>
    import { ref } from 'vue';
    const props = defineProps(['item']);

    const post = ref(props.item);
</script>
<template>
    <div class="flex flex-row w-full my-2 px-12 justify-start hover:scale-105">
       <slot name="body"
            :body="post.value?.body"
            :files="post.value?.files"
            :createdAt="post.value?.created_at"
            :mentions="post.value?.mentions"
            :user="post.value?.user"
            :liked="post.value?.liked"
            :reposted="post.value?.reposted"
            :repostedCount="post.value?.reposted_count"
            :comments="post.value?.comments"
            @commented="$emit('comment',$event)"
            @repost="$emit('repost')"
            @like="$emit('like')"
        />
    </div>
</template>
