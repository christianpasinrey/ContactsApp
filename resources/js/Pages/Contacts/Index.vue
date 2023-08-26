<script setup>
    import { ref, reactive, onBeforeMount, onMounted } from 'vue';
    import axios from 'axios';
    import { useToast } from 'vue-toast-notification';
    import Modal from '@/Components/Modal.vue';
    import ModalHead from '@/Components/ModalHead.vue';
    import CreateContactForm from '@/Pages/Contacts/Modals/CreateContact.vue';
    import FlexWrapList from '@/Components/Lists/FlexWrapList.vue';
    import ContactDataCard from '@/Components/List-Items/Cards/ContactCard.vue';

    const toast = useToast();
    const contacts = ref([]);
    const pagination = reactive({
        links: [],
        current_page: 1,
        prev_page_url: null,
    });
    const search_string = ref('');
    const timeout = ref(null);
    const loading = ref(true);
    const progress = ref(0);

    const newContactName = ref('');

    const selectedContact = ref(null);
    const selectedModal = ref(null);

    const toggleModal = (modal) => {
        selectedModal.value = modal;
    };

    const showModal = (modal) => {
        return selectedModal.value === modal;
    }

    const handleSearch = (e) => {
        if(e.target.value.length > 0 && e.key == 'Enter'){
            return createContact();
        }
        if(search_string.value.length < 3 && search_string.value.length > 0) {
            return;
        }
        return searchContacts();
    }
    const searchContacts = () => {
        clearTimeout(timeout.value);
        loading.value = true;
        progress.value = 0;
        const interval = setInterval(() => {
            progress.value += 10;
        }, 100);
        timeout.value = setTimeout(() => {
            axios.get(route('contacts.search',search_string.value))
            .then((response) => {
                contacts.value = response.data.data;
                pagination.links = response.data.links.filter(
                    (link) => !link.label.includes('Previous') && !link.label.includes('Next')
                );
                pagination.current_page = response.data.current_page;
                pagination.next_page_url = response.data.next_page_url || null;
                pagination.prev_page_url = response.data.prev_page_url;
                loading.value = false;
                progress.value = 100;
                clearInterval(interval);
            }).catch((error) => {
                console.log(error);
            });
        }, 700);
    };

    const changePage = (url) => {
        axios.get(url)
        .then((response) => {
            contacts.value = response.data.data;
            pagination.links = response.data.links.filter(
                (link) => !link.label.includes('Previous') && !link.label.includes('Next')
            );
            pagination.current_page = response.data.current_page;
            pagination.next_page_url = response.data.next_page_url || null;
            pagination.prev_page_url = response.data.prev_page_url;
        }).catch((error) => {
            console.log(error);
        });
    };

    const editContact = (id) => {
        selectedContact.value = contacts.value.find(
            (contact) => contact.id === id
        );
        toggleModal('edit');
    }

    const deleteContact = (id) => {
        selectedContact.value = contacts.value.find(
            (contact) => contact.id === id
        );
        toggleModal('delete');
    }

    const addContactToContactsList = (data) => {
        contacts.value.push(data);
        toggleModal(null);
        toast.success('Contacto creado exitosamente');
    };

    const createContact = () => {
        if(search_string.value.length > 0) {
            newContactName.value = search_string.value;
        }
        toggleModal('create');
    }
    onBeforeMount(() => {
        searchContacts();
    });

    onMounted(()=>{
        /* document.addEventListener('load',function() {
            var percent = Math.floor((Math.random() * 100) + 1) + "%";
            document.getElementById("progress-bar").innerHTML = percent;
        }); */
    })
</script>
<template>
    <div class="flex flex-row pt-4 justify-center">
        <div class="w-full md:w-6/12">
            <form>
                <input type="search"
                    class="w-full focus:ring-0 focus:border-b-slate-300 border-t-0 border-r-0 border-l-0 border-gray-300 px-4 py-2 text-gray-700 bg-slate-200 focus:shadow-inner rounded-tl-md"
                    placeholder="Buscar..."
                    v-model="search_string"
                    @keyup.prevent="handleSearch($event)"
                >
            </form>
        </div>
        <button
            @click.prevent="createContact"
            class="h-[41px] w-[60px] bg-green-500 hover:bg-green-700 text-white font-bold rounded-r-md flex justify-center align-middle items-center content-center">
            Crear
        </button>
    </div>
    <section name="loading-data"
        v-if="loading"
        class="flex flex-row my-12 items-center justify-center w-full h-full"
    >
        <progress
            id="progress-bar"
            max="100"
            :value="progress">
        </progress>
    </section>
    <section name="contacts-list"
        class="px-auto py-4"
        v-else
    >
        <FlexWrapList
            :data="contacts"
            @edit="editContact"
            @delete="deleteContact"
        >
            <template #item="{ item }">
                <ContactDataCard
                    :contact="item"
                    :key="`contact-${item.id}`" />
            </template>
        </FlexWrapList>
        <div class="flex flex-row w-full justify-center gap-3 py-3 absolute bottom-0 left-0">
            <button
                :disabled="!pagination.prev_page_url"
                class="bg-sky-500 hover:bg-sky-700 text-white font-bold py-2 px-4 rounded-l-md"
                :class="{
                    'opacity-50 cursor-not-allowed': !pagination.prev_page_url
                }"
                @click.prevent="changePage(pagination.prev_page_url)"
            >
                Anterior
            </button>
            <button
                v-for="link in pagination.links"
                :key="`link-${link.label}`"
                class="bg-sky-200 hover:bg-sky-700 text-gray-800 hover:text-gray-50 font-bold py-2 px-4"
                @click.prevent="changePage(link.url)"
            >
                {{ link.label }}
            </button>
            <button
                :disabled="!pagination.next_page_url"
                class="bg-sky-500 hover:bg-sky-700 text-white font-bold py-2 px-4 rounded-r-md"
                :class="{
                    'opacity-50 cursor-not-allowed': !pagination.next_page_url
                }"
                @click.prevent="changePage(pagination.next_page_url)"
            >
                Siguiente
            </button>
        </div>
    </section>

    <!-- CRUD MODALS -->

    <Modal
        :show="showModal('create')"
        :maxWidth="'full'"
        @close="toggleModal(null)"
        >
        <ModalHead
            title="Crear nuevo contacto"
            @close="toggleModal(null)"
        />
        <CreateContactForm
            :contactName="newContactName"
            @close="toggleModal(null)"
            @contactCreated="addContactToContactsList"
        />
    </Modal>
</template>
<style scoped>
    progress {
        background: #33333300;
        border-radius: 5%;
        height: 20px;
        width: 300px;
        padding: 1px;
        color:transparent;
    }

    progress[value]::-webkit-progress-value {
        background: linear-gradient(to right, #052658 0%, #0324b6 33%, #445fd6 66%, #9dacf3 100%);
        border-radius: 5%;
        height: 20px;
        width: 300px;
        padding: 3px;
    }

</style>
