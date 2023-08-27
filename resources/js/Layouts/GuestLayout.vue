<script setup>
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import { Link } from '@inertiajs/vue3';
</script>

<template>
    <div class="min-h-screen relative z-10 flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gray-100 dark:bg-gray-900">
        <div>
            <Link :href="route('welcome')" class="text-3xl font-medium">
                ContactsApp
            </Link>
        </div>

        <div
            class="w-full sm:max-w-md mt-6 px-6 py-4 bg-white dark:bg-gray-800 shadow-md overflow-hidden sm:rounded-lg"
        >
            <slot />
        </div>
        <img src="storage/main-welcome-bg.png" object-fit="cover" class="absolute inset-0 w-full h-full object-cover z-[-1]" alt="fondo-contactsApp">
    </div>
</template>
