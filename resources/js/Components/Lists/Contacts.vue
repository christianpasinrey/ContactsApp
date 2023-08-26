<script setup>
    import ContactCard from '@/Components/List-Items/Cards/ContactCard.vue';
    const props = defineProps(['contacts']);
</script>
<template>
    <div class="flex flex-row flex-wrap">
        <div class="contact-card"
            v-for="contact in contacts"
            :key="`contact-${contact.id}`"
        >
            <ContactCard
                :contact="contact"
            />
        </div>
    </div>
</template>
