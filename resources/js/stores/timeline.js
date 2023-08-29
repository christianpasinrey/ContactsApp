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

    return {
        timeline,
        fetchTimeline,
        loadMoreTimeline
    }
});
