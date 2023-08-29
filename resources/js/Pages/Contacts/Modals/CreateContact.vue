<script setup>
    import { ref, onBeforeMount, onMounted } from 'vue';
    import axios from 'axios';
    import { useToast } from 'vue-toast-notification';

    const toast = useToast();
    const props = defineProps({
        contactName:{
            type: String,
            required: false,
        }
    })
    const emits = defineEmits(['close', 'contactCreated']);

    const contactDataTypes = ref([
        {
            label: 'Teléfono',
            value: 'phone',
        },
        {
            label: 'Correo electrónico',
            value: 'email',
        },
        {
            label: 'LinkedIn',
            value: 'linkedin',
        },
        {
            label: 'Facebook',
            value: 'facebook',
        },
        {
            label: 'Instagram',
            value: 'instagram',
        },
    ]);
    const newContact = ref({
        name: '',
        contacts: [],
    });

    const newContactData = ref({
        type: 'phone',
        label: '',
        value: '',
        is_main: true,
    });

    const errors = ref({
        newContact: {
            name: {
                message: 'El nombre es requerido',
                status: false,
            },
            contacts: {
                message: 'Es necesario registrar al menos un medio de contacto',
                status: false,
            },
        },
        newContactData: {
            type: {
                message: 'El tipo de contacto es requerido',
                status: false,
            },
            value: {
                message: 'El contacto es requerido',
                status: false,
            },
        },
    });

    const addContactDataToNewContact = () => {
        let input = document.getElementById('contact_type');
        if (!newContactDataIsInvalid()) {
            newContact.value.contacts.push({
                type: newContactData.value.type,
                label: newContactData.value.label,
                value: newContactData.value.value,
                is_main: newContactData.value.is_main,
            });
            newContactData.value = {
                type: 'phone',
                label: '',
                value: '',
                is_main: true,
            };
            return input.focus();
        }
        return input.focus();
    }

    const removeContactDataFromNewContact = (contactData) => {
        newContact.value.contacts = newContact.value.contacts.filter(
            (cd) => cd.value !== contactData.value
        );
    }

    const cancelNewContact = () => {
        newContactData.value = {
            type: 'phone',
            label: '',
            value: '',
            is_main: true,
        };
        emits('close');
    }

    const newContactIsInvalid = () =>{
        let isInvalid = false;
        if(newContact.value.name === '') {
            errors.value.newContact.name.status = true;
            isInvalid = true;
        }
        if(newContact.value.contacts.length === 0) {
            errors.value.newContact.contacts.status = true;
            isInvalid = true;
        }
        return isInvalid;
    }

    const newContactDataIsInvalid = () => {
        let isInvalid = false;
        if (newContactData.value.value === '') {
            errors.value.newContactData.value.status = true;
            isInvalid = true;
        }
        return isInvalid;
    }
    const storeContact = () => {
        if (!newContactIsInvalid()) {
            axios.post(route('users.store'),
                newContact.value
            ).then((response) => {
                toast.success(response.data.message);
                newContact.value = {
                    name: '',
                    contacts: [],
                };
                newContactData.value = {
                    type: 'phone',
                    label: '',
                    value: '',
                    is_main: true,
                };
                emits('contactCreated', response.data.data);
            }).catch((error) => {
                toast.error('Error al añadir el contacto')
                console.log(error);
            });
        }

        return toast.info('Revisa los campos marcados en rojo');
    }
    onBeforeMount(() => {
        props.contactName ? newContact.value.name = props.contactName : '';
    });
    onMounted(() => {
        let input = document.getElementById('contact-type');
        newContact.value.name.length > 0 ? input.focus() : null;
    });
</script>
<template>
    <div class="flex flex-row w-full px-4 py-2">
        <form @submit.prevent="storeContact">
            <div class="flex flex-col">
                <label for="name" class="font-medium uppercase">Nombre</label>
                <input type="text"
                    class="rounded-md md:w-8/12 mb-4"
                    name="name"
                    id="name"
                    v-model="newContact.name"
                    >
                <span for="contacts" class="font-medium uppercase">Medios de contacto</span>
                <div class="flex flex-row relative pt-4 pb-8 align-middle items-center content-center gap-3">
                    <div class="flex flex-col">
                        <label for="contact-type">Tipo de contacto</label>
                        <select
                            class="rounded-md"
                            id="contact-type"
                            name="contact-type"
                            v-model="newContactData.type">
                            <option v-for="ct in contactDataTypes"
                                :key="`ct-${ct.value}`" :value="ct.value">
                                {{ ct.label }}
                            </option>
                        </select>
                    </div>
                    <div class="flex flex-col">
                        <label for="contact-label">Etiqueta</label>
                        <input
                            class="rounded-md"
                            type="text"
                            name="contact-label"
                            id="contact-label"
                            v-model="newContactData.label"
                            >
                        </div>
                    <div class="flex flex-col">
                        <label for="value">
                            {{
                                contactDataTypes.find(
                                    (cd) => cd.value === newContactData.type).label
                            }} (*)
                        </label>
                        <input
                            :type="newContactData.type === 'email' ? 'email' : 'text'"
                            name="value"
                            id="value"
                            class="rounded-md"
                            v-model="newContactData.value"
                            @keyup.enter.prevent="addContactDataToNewContact"
                        >
                            <small class="text-red-500"
                                :class="{
                                    'hidden': !errors.newContactData.value.status
                                }"
                            >
                            {{ errors.newContactData.value.message }}
                            </small>
                    </div>
                    <div class="flex flex-col justify-center">
                        <label class="mt-5 font-medium flex align-middle items-center content-center">
                            <input type="checkbox"
                                name="contact-is-main"
                                id="contact-is-main"
                                class="mx-2"
                                :checked="newContactData.is_main"
                                @keyup.space.prevent="newContactData.is_main = !newContactData.is_main"
                                @keyup.enter.prevent="addContactDataToNewContact"
                                v-model="newContactData.is_main">
                            Principal
                        </label>
                    </div>
                    <button
                        class="absolute bottom-0 right-0 mx-2 border text-sm bg-slate-200 shadow-md hover:bg-slate-300 rounded-md px-1 py-0.5"
                        @click.prevent="addContactDataToNewContact">
                        Añadir medio de contacto
                    </button>
                </div>
                <div
                    v-if="newContact.contacts.length > 0"
                    class="flex flex-row gap-3 mt-4 align-middle items-center content-center">
                    <table class="min-w-full">
                        <thead class="bg-gray-200 rounded-t-md text-left">
                            <tr>
                                <th class="rounded-tl-md px-2">Tipo</th>
                                <th>Etiqueta</th>
                                <th>Valor</th>
                                <th>Principal</th>
                                <th class="rounded-tr-md">&nbsp;</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="contactData in newContact.contacts"
                                :key="`contact-data-${contactData.id}`">
                                <td class="px-2">
                                    {{
                                        contactDataTypes.find(
                                            (cd) => cd.value === contactData.type
                                        ).label
                                    }}
                                </td>
                                <td>{{ contactData.label }}</td>
                                <td>{{ contactData.value }}</td>
                                <td>
                                    <input type="checkbox"
                                        :checked="contactData.is_main"
                                        disabled>
                                </td>
                                <td>
                                    <button
                                        class="text-red-500 hover:text-red-700"
                                        @click.prevent="removeContactDataFromNewContact(contactData)">
                                        &times;
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="5" class="text-end">
                                    <span>{{ newContact.contacts.length }}</span>
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
                <div class="flex flex-row relative pt-12 pb-8 justify-evenly">
                    <button
                        class="cancel-btn"
                        @click.prevent="cancelNewContact">
                        Cancelar
                    </button>
                    <button
                        @click.prevent="storeContact"
                        class="submit-btn">
                        Crear
                    </button>
                    <small class="absolute bottom-0 right-0 m-2 text-xs">
                        (*) Campos requeridos
                    </small>
                </div>
            </div>
        </form>
    </div>
</template>
