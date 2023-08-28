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

    // actions
    function setAuthUser(user) {
        authUser.value = user;
    }

    function fetchUser(id) {
        axios.get(route('users.show', id))
        .then(response => {
            return setSelectedUser(response.data);
        })
        .catch(error => {
            console.log(error);
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
+        axios.post(route('posts.store'),formData,{
            headers: {
                'Content-Type': 'multipart/form-data'
            }
        }).then(response => {
            authUser.value?.posts.unshift(response.data.data);
        }).catch(error => {
            console.log(error);
        });
    }

    // getters
    function getAuthUser() {
        return authUser.value;
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
        users,
        selectedUser,
        setAuthUser,
        fetchUser,
        setSelectedUser,
        addSelectedUserAsContact,
        toggleUserInSelectedUsers,
        createPost,
        setUsers,
        getAuthUser,
        getUserById,
        getUsersList,
        getSelectedUser,
        getSelectedUsers
    }
})
