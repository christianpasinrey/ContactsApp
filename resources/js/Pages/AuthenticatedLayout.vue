<script setup>
import { onBeforeMount, onMounted, ref } from 'vue';
import Dropdown from '@/Components/Dropdown.vue';
import DropdownLink from '@/Components/DropdownLink.vue';
import ResponsiveNavLink from '@/Components/ResponsiveNavLink.vue';
import { Link } from '@inertiajs/vue3';
import { useDark } from '@vueuse/core';
import { useRouter } from 'vue-router';
import { usePage } from '@inertiajs/vue3';
import { useUsersStore } from '@/Stores/User';

const usersStore = useUsersStore();
const page = usePage();
const vueRouter = useRouter();
const isDark = useDark();

function toggleDark() {
    isDark.value = !isDark.value;
}
const showingNavigationDropdown = ref(false);

const searchUsersString = ref('');
const searchTimeout = ref(null);

const searchUsers = () => {
    axios.get(route('users.search', searchUsersString.value))
    .then((response)=>{
        usersStore.users = response.data;
    }).catch((error)=>{
        console.log(error);
    });
}

const handleSearchUsers = (event) => {
    clearTimeout(searchTimeout.value);
    if(event.key == 'Enter'){
        let element = document.getElementById('advanced-search-page-button');
        element.click();
        return;
    }
    searchTimeout.value = setTimeout(()=>{
        if(searchUsersString.value.length < 3 && searchUsersString.value.length > 0) {
            return;
        }
        return searchUsers();
    }, 700);
}

const goToUserProfile = (user) => {
    usersStore.setUsers([]);
    usersStore.setSelectedUser(user);
    vueRouter.push({
        name: 'users.profile',
        params: { id: user.id },
    });
}


function getUserData() {
    usersStore.authUser = page.props.auth.user;
}

onMounted(() => {
    let element = document.getElementById('users-list');
    document.addEventListener('click', (event) => {
        if(element.classList.contains('block') && !element.contains(event.target)){
            usersStore.setUsers([]);
        }
    });
});

onBeforeMount(() => {
    getUserData();
});
</script>

<template>
    <div>
        <div class="min-h-screen relative flex">
            <nav class="font-robotoBlack hidden md:flex flex-col z-[999] md:w-3/12 h-screen shadow-md bg-slate-200 dark:bg-slate-700 border-b border-gray-100 dark:border-gray-700">
                <div class="flex flex-col text-center items-center content-center">
                    <!-- Logo -->
                    <div class="shrink-0 flex items-center dark:text-gray-50 font-bold my-6">
                        <RouterLink to="dashboard" >
                            <span class="nav-link-btn">
                                Contacts
                            </span>
                        </RouterLink>
                    </div>
                    <div class="flex my-12">
                        <Dropdown align="right" width="36">
                            <template #trigger>
                                <span class="inline-flex rounded-md">
                                    <button
                                        type="button"
                                        class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 dark:text-gray-400 bg-white dark:bg-gray-800 hover:text-gray-700 dark:hover:text-gray-300 focus:outline-none transition ease-in-out duration-150"
                                    >
                                        {{ $page.props.auth.user.name }}

                                        <svg
                                            class="ml-2 -mr-0.5 h-4 w-4"
                                            xmlns="http://www.w3.org/2000/svg"
                                            viewBox="0 0 20 20"
                                            fill="currentColor"
                                        >
                                            <path
                                                fill-rule="evenodd"
                                                d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                                clip-rule="evenodd"
                                            />
                                        </svg>
                                    </button>
                                </span>
                            </template>

                            <template #content>
                                <DropdownLink
                                    :href="route('logout')"
                                    method="post"
                                    as="button">
                                    Log Out
                                </DropdownLink>
                                <button
                                    class="block w-full px-4 py-2 text-left text-sm leading-5 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800 focus:outline-none focus:bg-gray-100 dark:focus:bg-gray-800 transition duration-150 ease-in-out"
                                    @click.prevent="toggleDark">
        <!--                             <svg
                                        class="w-5 h-5"
                                        viewBox="0 0 24 24"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path v-if="isDark" fill="currentColor" d="M10.999-.004h2.004V2h-2.004V-.004ZM4.223 2.803L5.64 4.22L4.223 5.637L2.806 4.22l1.417-1.417Zm15.556 0l1.417 1.417l-1.417 1.417l-1.417-1.417l1.417-1.417ZM12 6a6 6 0 1 0 0 12a6 6 0 0 0 0-12Zm-8 6a8 8 0 1 1 16 0a8 8 0 0 1-16 0Zm-4.001-1.004h2.004V13H-.001v-2.004Zm22 0h2.004V13h-2.004v-2.004ZM4.223 18.36l1.417 1.417l-1.417 1.418l-1.417-1.418l1.417-1.417Zm15.556 0l1.417 1.417l-1.417 1.417l-1.417-1.417l1.417-1.416ZM11 21.997h2.004V24H11v-2.004Z"/>
                                        <path v-else fill="currentColor" d="M10.999-.004h2.004V2h-2.004V-.004ZM4.223 2.803L5.64 4.22L4.223 5.637L2.806 4.22l1.417-1.417Zm15.556 0l1.417 1.417l-1.417 1.417l-1.417-1.417l1.417-1.417ZM12 6a6 6 0 1 0 0 12a6 6 0 0 0 0-12Zm-8 6a8 8 0 1 1 16 0a8 8 0 0 1-16 0Zm-4.001-1.004h2.004V13H-.001v-2.004Zm22 0h2.004V13h-2.004v-2.004ZM4.223 18.36l1.417 1.417l-1.417 1.418l-1.417-1.418l1.417-1.417Zm15.556 0l1.417 1.417l-1.417 1.417l-1.417-1.417l1.417-1.416ZM11 21.997h2.004V24H11v-2.004Z"/>

                                    </svg> -->
                                    <span v-if="isDark">Tema claro</span>
                                    <span v-else>Tema oscuro</span>
                                </button>
                            </template>
                        </Dropdown>
                    </div>

                    <!-- Navigation Links -->
                    <div class="hidden sm:ml-10 md:ml-0 sm:flex md:flex-col w-full content-center text-center my-12">
                        <RouterLink to="/dashboard">
                            <span class="nav-link-btn">
                                Inicio
                            </span>
                        </RouterLink>
                        <RouterLink to="/contacts">
                            <span class="nav-link-btn">
                                Contactos
                            </span>
                        </RouterLink>
                        <RouterLink to="/live-news">
                            <span class="nav-link-btn">
                                Noticias
                            </span>
                        </RouterLink>
                        <RouterLink to="/advanced-search" id="advanced-search-page-button" class="hidden">
                        </RouterLink>
                    </div>
                </div>
                <!-- Responsive Navigation Menu -->
                <div
                    :class="{ block: showingNavigationDropdown, hidden: !showingNavigationDropdown }"
                    class="sm:hidden"
                >
                    <div class="pt-2 pb-3 space-y-1">
                        <RouterLink to="/dashboard">
                            Dashboard
                        </RouterLink>
                    </div>

                    <!-- Responsive Settings Options -->
                    <div class="pt-4 pb-1 border-t border-gray-200 dark:border-gray-600">
                        <div class="px-4">
                            <div class="font-medium text-base text-gray-800 dark:text-gray-200">
                                {{ $page.props.auth.user.name }}
                            </div>
                            <div class="font-medium text-sm text-gray-500">{{ $page.props.auth.user.email }}</div>
                        </div>

                        <div class="mt-3 space-y-1">
                            <ResponsiveNavLink :href="route('logout')" method="post" as="button">
                                Log Out
                            </ResponsiveNavLink>
                        </div>
                    </div>
                </div>
            </nav>

            <!-- Page Content -->
            <main class="flex flex-col h-screen z-10 relative w-full bg-slate-100 dark:bg-slate-600 font-robotoLight">
                <RouterView :key="$route.fullPath" />
                <slot />
            </main>
            <aside class="hidden md:flex md:flex-col md:w-5/12 relative bg-slate-200 dark:bg-slate-700">
                <div class="shrink-0 flex items-center px-0 dark:text-gray-50 font-bold absolute top-6 left-0 w-full lg:w-8/12">
                    <div class="relative flex w-full">
                        <input type="search"
                        class="w-full mx-6 py-0.5 rounded-md bg-gray-100 dark:bg-gray-700 border-transparent focus:border-gray-500 focus:bg-white focus:ring-0"
                        placeholder="Buscar..."
                        v-model="searchUsersString"
                        @keyup.prevent="handleSearchUsers($event)"
                        >
                        <output
                            :class="{
                                'hidden': usersStore.users?.length == 0,
                                'block': usersStore.users?.length > 0
                            }"
                            id="users-list"
                            class="absolute top-8 ml-6 z-[1000] shadow-md rounded-b-md h-fit w-10/12 max-h-64 overflow-y-auto transition-all duration-700 ease-in-out bg-slate-100 dark:bg-slate-400 ">
                            <ol>
                                <li
                                    v-for="user in usersStore.users"
                                    :key="`user-${user.id}`"
                                    id="user-item-list"
                                    style="list-style-type: none;"
                                    class="text-black text-start px-2 dark:text-gray-50 dark:font-medium cursor-pointer w-full hover:bg-slate-200 dark:hover:bg-slate-600"
                                    :class="{
                                        'py-1.5' : usersStore.users.indexOf(user) == 0,
                                        'py-1' : usersStore.users.indexOf(user) != 0,
                                        'border-b border-gray-300 dark:border-gray-700': usersStore.users.indexOf(user) != usersStore.users.length - 1
                                    }"
                                    @click.prevent="goToUserProfile(user)"
                                >
                                    {{ user.name }}
                                </li>
                            </ol>
                        </output>
                    </div>
                </div>
            </aside>
            <div class="absolute inset-0 w-full h-full bg-slate-700 dark:bg-slate-950 opacity-50 z-[-1]"></div>
        </div>
    </div>
</template>
<style>
.router-link-active.router-link-exact-active {
    color: #e53e3e !important;
    border-color: #e53e3e !important;
}
::-webkit-scrollbar {
    display: none;
}
</style>
