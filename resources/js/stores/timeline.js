import { defineStore } from 'pinia'
import { ref } from 'vue'
import axios from 'axios'
// You can name the return value of `defineStore()` anything you want,
// but it's best to use the name of the store and surround it with `use`
// and `Store` (e.g. `useUserStore`, `useCartStore`, `useProductStore`)
// the first argument is a unique id of the store across your application
export const useTimelineStore = defineStore('timeline', () => {
    const id = ref(null);
    const timeline = ref(null);

    const fetchTimeline = (userId) => {
        id.value = userId;
        axios.get(route('users.timeline',id.value))
        .then(response => {
            timeline.value = response.data;
        }).catch(error => {
            console.log(error);
        });
    }

    const loadMoreTimeline = () => {
        if(timeline.value.next_page_url != null){
            axios.get(route('users.timeline',id.value) + `?page=${timeline.value.current_page + 1}`)
            .then(response => {
                timeline.value.current_page = response.data.current_page;
                timeline.value.next_page_url = response.data.next_page_url ? timeline.value.current_page + 1 : null;
                timeline.value.data = timeline.value.data.concat(response.data.data);
            }).catch(error => {
                console.log(error);
            });
        }
    }

    const repostPost = (postId) => {
        axios.post(route('posts.repost',postId))
        .then(response => {
            timeline.value.data.unshift(response.data);
        }).catch(error => {
            console.log(error);
        });
    }

    const likePost = (postId) => {
        axios.post(route('posts.like',postId))
        .then(response => {
            timeline.value.data = timeline.value.data.map(post => {
                if(post.id == postId){
                    post.likes_count = response.data.likes_count;
                    post.is_liked = response.data.is_liked;
                }
                return post;
            });
        }).catch(error => {
            console.log(error);
        });
    }

    const commentPost = (postId,comment) => {
        axios.post(route('posts.comment',postId),{
            body: comment
        }).then((response)=>{
            timeline.value.data.find((post)=>{
                if(post.id == postId){
                    post.comments.push(response.data);
                    post.comments_count++;
                }
            });
        }).catch((error)=>{
            console.log(error);
        });
    }

    return {
        timeline,
        fetchTimeline,
        loadMoreTimeline,
        repostPost,
        likePost,
        commentPost
    }
});
