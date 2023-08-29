<script setup>
import { onBeforeMount, onMounted, ref } from 'vue';
import Dropdown from '@/Components/Dropdown.vue';
import DropdownLink from '@/Components/DropdownLink.vue';
import ResponsiveNavLink from '@/Components/ResponsiveNavLink.vue';
import { Link } from '@inertiajs/vue3';
import { useDark } from '@vueuse/core';
import { useRouter } from 'vue-router';
import { usePage } from '@inertiajs/vue3';
import  { useUsersStore } from '@/Stores/user';

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
        <div class="min-h-screen dark:bg-gray-900 relative">
            <nav class="fixed top-0 left-0 z-[999] w-screen h-[7vh] bg-gradient-to-r from-slate-200 via-gray-300 to-sky-500 dark:from-slate-500 dark:via-gray-600 dark:to-sky-950 dark:bg-gray-800 border-b border-gray-100 dark:border-gray-700">
                <!-- Primary Navigation Menu -->
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="flex justify-between h-16">
                        <div class="flex">
                            <!-- Logo -->
                            <div class="shrink-0 flex items-center dark:text-gray-50 font-bold ">
                                <Link :href="route('home')">
                                    ContactsApp
                                </Link>
                            </div>

                            <!-- Navigation Links -->
                            <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex">
                                <RouterLink to="/dashboard">
                                    <span class="inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-medium leading-5 text-gray-500 dark:text-gray-200 hover:text-gray-700 dark:hover:text-gray-300 hover:border-gray-300 dark:hover:border-gray-700 focus:outline-none focus:text-gray-700 dark:focus:text-gray-300 focus:border-gray-300 dark:focus:border-gray-700 transition duration-150 ease-in-out mt-5">
                                        Inicio
                                    </span>
                                </RouterLink>
                                <RouterLink to="/contacts">
                                    <span class="inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-medium leading-5 text-gray-500 dark:text-gray-200 hover:text-gray-700 dark:hover:text-gray-300 hover:border-gray-300 dark:hover:border-gray-700 focus:outline-none focus:text-gray-700 dark:focus:text-gray-300 focus:border-gray-300 dark:focus:border-gray-700 transition duration-150 ease-in-out mt-5">
                                        Contactos
                                    </span>
                                </RouterLink>
                                <RouterLink to="/advanced-search" id="advanced-search-page-button" class="hidden">
                                </RouterLink>
                            </div>
                            <div class="shrink-0 flex items-center px-0 dark:text-gray-50 font-bold w-full">
                                <div class="relative flex w-10/12">
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
                                        class="absolute top-8 ml-6 z-[1000] shadow-md rounded-b-md h-fit w-11/12 max-h-64 overflow-y-auto transition-all duration-700 ease-in-out bg-slate-100 dark:bg-slate-400 ">
                                        <ol>
                                            <li
                                                v-for="user in usersStore.users"
                                                :key="`user-${user.id}`"
                                                id="user-item-list"
                                                style="list-style-type: none;"
                                                class="text-black px-2 dark:text-gray-50 dark:font-medium cursor-pointer w-full hover:bg-slate-200 dark:hover:bg-slate-600"
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
                        </div>

                        <div class="hidden sm:flex sm:items-center sm:ml-6">
                            <!-- Settings Dropdown -->
                            <div class="ml-3 relative">
                                <Dropdown align="right" width="48">
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
                        </div>

                        <!-- Hamburger -->
                        <div class="-mr-2 flex items-center sm:hidden">
                            <button
                                @click="showingNavigationDropdown = !showingNavigationDropdown"
                                class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 dark:text-gray-500 hover:text-gray-500 dark:hover:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-900 focus:outline-none focus:bg-gray-100 dark:focus:bg-gray-900 focus:text-gray-500 dark:focus:text-gray-400 transition duration-150 ease-in-out"
                            >
                                <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                                    <path
                                        :class="{
                                            hidden: showingNavigationDropdown,
                                            'inline-flex': !showingNavigationDropdown,
                                        }"
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M4 6h16M4 12h16M4 18h16"
                                    />
                                    <path
                                        :class="{
                                            hidden: !showingNavigationDropdown,
                                            'inline-flex': showingNavigationDropdown,
                                        }"
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12"
                                    />
                                </svg>
                            </button>
                        </div>
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

            <!-- Page Heading -->
            <header class="bg-white dark:bg-gray-800 shadow" v-if="$slots.header">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    <slot name="header" />
                </div>
            </header>

            <!-- Page Content -->
            <main class="absolute top-[7vh] left-0 bg-slate-100 dark:bg-slate-700 w-full z-10 min-h-[93vh]">
                <RouterView :key="$route.fullPath" />
                <img src="storage/app-bg.png" object-fit="cover" class="absolute inset-0 w-full h-full object-cover z-[-2] dark:opacity-60" alt="fondo-contactsApp">
                <div class="absolute inset-0 w-full h-full bg-slate-100 dark:bg-slate-600 opacity-50 z-[-1]"></div>
            </main>
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
