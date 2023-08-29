import { defineStore } from 'pinia'
import { ref } from 'vue'
import axios from 'axios'
// You can name the return value of `defineStore()` anything you want,
// but it's best to use the name of the store and surround it with `use`
// and `Store` (e.g. `useUserStore`, `useCartStore`, `useProductStore`)
// the first argument is a unique id of the store across your application
export const useUsersStore = defineStore('users', () => {
    // shared state
    const authUser = ref(null);
    const users = ref(null);
    const selectedUser = ref(null);
    const selectedUsers = ref([]);
    const timeline_current_page = ref(null);
    const timeline_next_page_url = ref(null);
    const timeline = ref(null);

    // actions
    function setAuthUser(user) {
        authUser.value = user;
    }

    function fetchUser(id) {
        return new Promise((resolve, reject) => {
            axios.get(route('users.show', id))
                .then(response => {
                    if(id !== authUser?.value.id){
                        selectedUser.value = response.data;
                    }else{
                        authUser.value = response.data;
                    }
                    resolve(response.data);
                })
                .catch(error => {
                    reject(error);
                });
        });
    }

    function setUsers(array) {
        users.value = array;
    }

    function setSelectedUser(user) {
        selectedUser.value = user;
    }

    function toggleUserInSelectedUsers(user) {
        const index = selectedUsers.value.findIndex(u => u.id === user.id);
        if (index === -1) {
            selectedUsers.value.push(user);
        } else {
            selectedUsers.value.splice(index, 1);
        }
    }

    const addSelectedUserAsContact = () =>{
        axios.post(route('users.contacts.add'),selectedUser.value)
        .then(response => {
            authUser.value?.contacts.push(response.data);
        }).catch(error => {
            console.log(error);
        });
    }

    const createPost = (data) => {
        let formData = new FormData();
        formData.append('body', data.body);
        formData.append('user_id', authUser.value.id);
        if(data.files){
            for (let i = 0; i < data.files.length; i++) {
                formData.append(i, data.files[i]);
            }
        }
        if(data.mentions){
            formData.append('mentions', data.mentions);
        }
+        axios.post(route('posts.store'),formData,{
            headers: {
                'Content-Type': 'multipart/form-data'
            }
        }).then(response => {
            fetchTimeline()
        }).catch(error => {
            console.log(error);
        });
    }

    const handleFetchTimeline = () => {
        if(timeline_next_page_url.value != null){
           return fetchTimeline();
        }
        return;
    }
    const fetchTimeline = () => {
        axios.get(route('users.timeline',authUser.value?.id))
        .then(response => {
            timeline.value = response.data;
        }).catch(error => {
            console.log(error);
        });
    }

    const loadMoreTimeline = () => {
        if(timeline.value.next_page_url != null){
            axios.get(route('users.timeline',authUser.value?.id) + `?page=${timeline.value.current_page + 1}`)
            .then(response => {
                timeline.value.current_page = response.data.current_page;
                timeline.value.next_page_url = response.data.next_page_url ? timeline.value.current_page + 1 : null;
                timeline.value.data = timeline.value.data.concat(response.data.data);
            }).catch(error => {
                console.log(error);
            });
        }
    }

    function getUserById(id) {
        return users.value.find(user => user.id === id);
    }

    const getUsersList = () => {
        return users.value;
    }

    function getSelectedUser() {
        return selectedUser.value;
    }

    function getSelectedUsers() {
        return selectedUsers.value;
    }
    // @ts-ignore
    return {
        authUser,
        timeline_current_page,
        timeline_next_page_url,
        timeline,
        users,
        selectedUser,
        setAuthUser,
        fetchUser,
        setSelectedUser,
        addSelectedUserAsContact,
        toggleUserInSelectedUsers,
        createPost,
        handleFetchTimeline,
        fetchTimeline,
        loadMoreTimeline,
        setUsers,
        getUserById,
        getUsersList,
        getSelectedUser,
        getSelectedUsers
    }
})
