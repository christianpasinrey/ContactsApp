<script setup>
    import { useUsersStore } from '@/Stores/User';
    import { useRoute } from 'vue-router';
    import { watch,ref } from 'vue';
    import SocialIcons from '@/Components/SocialIcons.vue';
    import SidebarContactCard from '@/Components/List-items/Cards/SidebarContactCard.vue';
    import FlexWrapList from '@/Components/Lists/FlexWrapList.vue';
    import Modal from '@/Components/Modal.vue';
    import ModalHead from '@/Components/ModalHead.vue';

    const usersStore = useUsersStore();
    const vueRoute = useRoute();

    const user = usersStore.selectedUser;
    const selectedModal = ref(null);

    const toggleModal = (modal) => {
        selectedModal.value = modal;
    };

    const showModal = (modal) => {
        return selectedModal.value === modal;
    }

    const addContactConfirmed = () => {
        usersStore.addSelectedUserAsContact();
        toggleModal(null);
    }
    watch(
        () => vueRoute.params.id,
        (id) => {
            usersStore.fetchUser(id);
        },
        { immediate: true }
    );
</script>
<template>
    <div>
        <section
            class="flex flex-row justify-center items-center gap-4 py-4 relative"
        >
            <div class="flex flex-col">
                <div class="mt-12">
                    <div class="flex flex-row w-full gap-4">
                        <h4 class="text-4xl font-bold text-gray-100">{{ user?.name }}</h4>

                    </div>
                    <span class="text-md text-gray-200 mb-4"> {{ user?.email }}</span>
                </div>
                <div class="my-3 justify-center flex">
                    <SocialIcons
                        :contact="user"
                        :size="2"
                    />
                </div>
                <div class="my-3 justify-center flex">
                    <button
                        v-if="!user?.is_contact_of_auth_user"
                        @click.prevent="toggleModal('add-contact')"
                        class="w-fit py-0.5 px-2 hover:scale-110 hover:shadow-lg hover:ring-1 hover:ring-blue-700 rounded-md text-gray-100 font-medium bg-sky-700 hover:bg-blue-500 transition-all duration-500 ease-in-out">
                        AÑADIR
                    </button>
                </div>
            </div>

        </section>
        <section
            class="flex flex-row justify-center items-center gap-4 py-4"
        >
            <div class="flex flex-col px-4">
                <div
                    class="flex flex-row justify-center"
                    v-for="e in user?.emails"
                    :key="`contactdata-${e.id}`"
                    >
                    <span class="text-gray-100 font-medium cursor-default">{{ e.value }}</span>
                </div>
                <div
                    class="flex flex-row justify-center"
                    v-for="p in user?.phones"
                    :key="`contactdata-${p.id}`"
                    >
                    <span class="text-gray-100 font-medium cursor-default">{{ p.value }}</span>
                </div>
            </div>
        </section>
        <section>
            <div class="flex flex-row flex-wrap">
                <FlexWrapList
                    v-if="user?.contacts"
                    :data="user?.contacts">
                    <template #item="{ item }">
                        <SidebarContactCard
                            :contact="item"
                            :key="`contact-${item.id}`" />
                    </template>
                </FlexWrapList>
            </div>
        </section>
        <section
            class="flex flex-row">
            {{ user?.posts }}
        </section>
        <Modal :show="showModal('add-contact')">
            <ModalHead title="Añadir contacto" @close="toggleModal(null)" />
            <div class="flex flex-col py-6">
                <div class="flex flex-row px-6">
                    <p class="dark:text-gray-100">
                        ¿Estás seguro de que quieres añadir a
                        <em><strong class="dark:text-gray-500"> {{ user?.name }} </strong></em>
                        como contacto?
                    </p>
                </div>
                <div class="flex flex-row px-4 justify-center gap-3 py-3">
                    <button
                        @click.prevent="toggleModal(null)"
                        class="cancel-btn">
                        Cancelar
                    </button>
                    <button
                        @click.prevent="addContactConfirmed"
                        class="submit-btn">
                        Añadir
                    </button>
                </div>
            </div>
        </Modal>
    </div>
</template>
