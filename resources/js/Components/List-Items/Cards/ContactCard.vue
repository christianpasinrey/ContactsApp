<script setup>
    import SocialMediaIcons from '../../SocialIcons.vue';

    const emits = defineEmits(['edit','delete']);
    const props = defineProps(['contact'])
</script>
<template>
    <div class="contact-card">
        <div class="flex flex-row justify-between">
            <span
                id="contact-name"
                class="font-medium uppercase text-lg"
                >{{ contact.name }}
            </span>
            <SocialMediaIcons
                :contact="contact"></SocialMediaIcons>
        </div>
        <div class="flex flex-row relative gap-3 pl-4 mt-2"
            v-for="email in contact.emails"
            :key="`email-${email.id}`"
        >
            <div class="flex flex-col w-[15px] justify-center">
                <span class="w-fit">
                    <svg
                        class="w-4 h-4"
                        viewBox="0 0 20 20"
                        xmlns="http://www.w3.org/2000/svg">
                        <path fill="#000000" d="M14.608 12.172c0 .84.239 1.175.864 1.175c1.393 0 2.28-1.775 2.28-4.727c0-4.512-3.288-6.672-7.393-6.672c-4.223 0-8.064 2.832-8.064 8.184c0 5.112 3.36 7.896 8.52 7.896c1.752 0 2.928-.192 4.727-.792l.386 1.607c-1.776.577-3.674.744-5.137.744c-6.768 0-10.393-3.72-10.393-9.456c0-5.784 4.201-9.72 9.985-9.72c6.024 0 9.215 3.6 9.215 8.016c0 3.744-1.175 6.6-4.871 6.6c-1.681 0-2.784-.672-2.928-2.161c-.432 1.656-1.584 2.161-3.145 2.161c-2.088 0-3.84-1.609-3.84-4.848c0-3.264 1.537-5.28 4.297-5.28c1.464 0 2.376.576 2.782 1.488l.697-1.272h2.016v7.057h.002zm-2.951-3.168c0-1.319-.985-1.872-1.801-1.872c-.888 0-1.871.719-1.871 2.832c0 1.68.744 2.616 1.871 2.616c.792 0 1.801-.504 1.801-1.896v-1.68z"/>
                    </svg>
                </span>
            </div>
            <div class="flex flex-col">
                {{ email.label }}
            </div>
            <div class="flex flex-col">
                {{ email.value }}
            </div>
            <span v-if="email.is_main"
                >
                <svg class="w-6 h-6 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path d="M5 13l4 4L19 7"></path>
                </svg>
            </span>
        </div>
        <div class="flex flex-row relative gap-3 pl-4 mt-2"
            v-for="phone in contact.phones"
            :key="`phone-${phone.id}`"
        >
            <div class="flex flex-col w-[15px] justify-center">
                <span class="w-fit">
                    <svg
                        class="w-4 h-4"
                        viewBox="0 0 24 24"
                        xmlns="http://www.w3.org/2000/svg">
                        <path fill="#000000" d="M6.62 10.79c1.44 2.83 3.76 5.14 6.59 6.59l2.2-2.2c.27-.27.67-.36 1.02-.24c1.12.37 2.33.57 3.57.57c.55 0 1 .45 1 1V20c0 .55-.45 1-1 1c-9.39 0-17-7.61-17-17c0-.55.45-1 1-1h3.5c.55 0 1 .45 1 1c0 1.25.2 2.45.57 3.57c.11.35.03.74-.25 1.02l-2.2 2.2z"/>
                    </svg>

                </span>
            </div>
            <div class="flex flex-col">
                {{ phone.label }}
            </div>
            <div class="flex flex-col">
                {{ phone.value }}
            </div>
            <span v-if="phone.is_main"
                >
                <svg class="w-6 h-6 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path d="M5 13l4 4L19 7"></path>
                </svg>
            </span>
        </div>
        <span class="absolute bottom-0 rigth-0 m-1.5">
            <span
                class="text-xs hover:scale-110 cursor-pointer"
                v-if="contact.emails.length > 1 || contact.phones.length > 1"
                @click.prevent="toggleModal('show-more')"
            >
                Ver más
            </span>
        </span>
    </div>
</template>
