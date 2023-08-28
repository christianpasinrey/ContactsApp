<script setup>
    import { ref, computed } from 'vue';
    import { useUsersStore } from '@/Stores/user';
    import { useRouter } from 'vue-router';
    const URL = window.URL || window.webkitURL;

    const vueRouter = useRouter();
    const usersStore = useUsersStore();

    const newPostTextarea = ref(null);
    const newPost = ref('');
    const files = ref([]);
    const mentions = ref([]);
    const isMention = ref(false);
    const mentionWord = ref('');
    const mentionOptions = ref([]);
    const selectPosition = ref(0);

    const fileInput = ref(null);
    const textAreaFocused = ref(false);

    const maxFileNumber = 4;
    const maxFileSize = 1024 * 1024 * 5;
    const fileError = ref(false);

    const maxFileSizeExceeded = (file) => {
        file.size > maxFileSize ? fileError.value = true : fileError.value = false;
        return fileError.value;
    };

    const maxFileNumberExceeded = (files) => {
        files.length > maxFileNumber ? fileError.value = true : fileError.value = false;
        return fileError.value;
    };

    const handleFiles = (event) => {
        const fileList = event.target.files;
        if (maxFileNumberExceeded(fileList)) {
            alert(`Solo puedes subir ${maxFileNumber} archivos`);
            return;
        }
        if (Array.from(fileList).some(maxFileSizeExceeded)) {
            alert(`Solo puedes subir archivos de hasta ${maxFileSize / 1024 / 1024}MB`);
            return;
        }
        files.value = [...fileList];
    };

    const preparePost = () => {
        let formData = {
            body: newPost.value,
        };
        if(mentions.value.length > 0){
            formData.mentions = mentions.value.map((user) => user.id);
        }
        if(files.value.length > 0)
        {
            formData.files = files.value;
            usersStore.createPost(formData);
            newPost.value = '';
            files.value = [];
            return;
        }
        usersStore.createPost(formData);
        newPost.value = '';
        newPostTextarea.value.innerHTML = '';
        files.value = [];
        mentions.value = [];
    };

    const filteredUsersToMention = computed(()=>{
        return usersStore.authUser?.contacts.filter((user) =>
            user.name.toLowerCase().includes(mentionWord.value.toLowerCase())
        );
    })

    const handleTextareaKeyup = (event) => {
        selectPosition.value = window.getSelection().anchorOffset;
        //tranform multiple spaces into one
        newPostTextarea.value.innerHTML.replace(/&nbsp;/g, ' ');
        const words = event.target.innerText.split(' ');
        const lastWord = words[words.length - 1];
        if (lastWord.startsWith('@')) {
            isMention.value = true;
            mentionWord.value = lastWord.slice(1);
        } else {
            isMention.value = false;
            mentionWord.value = '';
            mentionOptions.value = [];
        }
        newPostTextarea.value.focus();
        newPost.value = newPostTextarea.value.innerHTML;
    }

    const handleMention = (id) => {
        //delete the word and the @
        newPostTextarea.value.innerHTML = newPostTextarea.value.innerHTML.slice(0, -mentionWord.value.length - 1);
        let user = usersStore.authUser?.contacts.find((user) => user.id === parseInt(id));
        if (user && !mentions.value.includes(user)) {
            mentions.value.push(user);
            usersStore.selectedUser = user;
        }else{
            isMention.value = false;
            mentionWord.value = '';
            mentionOptions.value = [];
            return toast.error('No se pudo mencionar al usuario');
        }

        let newElement = `<span id="${user.id}" class="text-sky-600 font-bold cursor-pointer user-mentioned">${user.name}</span>`;
        newPostTextarea.value.innerHTML += newElement + '<span>&nbsp;</span>';
        newPost.value = newPostTextarea.value.innerHTML;
        isMention.value = false;
        mentionWord.value = '';
        mentionOptions.value = [];
        newPostTextarea.value.scrollTop = newPostTextarea.value.scrollHeight;
        //focus on the end of the text of newPostTextarea
        placeCaretAtEnd();
    }

    const placeCaretAtEnd = () => {
        let range = document.createRange();
        let sel = window.getSelection();
        range.setStart(newPostTextarea.value.lastChild, 1);
        range.collapse(true);
        sel.removeAllRanges();
        sel.addRange(range);

        newPostTextarea.value.focus();
    }

    const goToUserProfile = (id) => {
        vueRouter.push({ name: 'users.profile', params: { id } });
    }
</script>
<template>
    <div class="new-post-card"
        :class="{
            'ring-1 ring-blue-700 ' : textAreaFocused,
            'ring-0' : !textAreaFocused,
        }"
    >
        <div id="new-post-text">
            <div contenteditable="true"
                id="new-post-textarea"
                ref="newPostTextarea"
                @keyup="handleTextareaKeyup"
                @focus="textAreaFocused = true"
                @blur="textAreaFocused = false"
                class="w-full rounded-t-md cursor-text py-1 px-2 h-24 bg-sky-100 focus:outline-none focus:border-transparent focus:ring-0 focus:border-slate-200"
                placeholder="¿Qué estás pensando?"
            >
            </div>
            <select
                v-if="isMention"
                id="select-mention"
                class="absolute top-10 w-fit h-10 bg-sky-200 rounded-md text-gray-800"
                :class="`left-${selectPosition}`"
                style="z-index: 1000"
                @input="handleMention($event.target.value)"
            >
                <option
                    v-for="option in filteredUsersToMention"
                    :key="`mention-option-${option.id}`"
                    :value="option.id"
                >
                    {{ option.name }}
                </option>
            </select>
        </div>

        <div
            class="h-fit w-full flex flex-row justify-start align-bottom items-end content-end"
        >
            <input
                type="file"
                class="hidden"
                multiple
                accept="*"
                ref="fileInput"
                @change="handleFiles"
            />
            <button
                @click="$refs.fileInput.click()"
                class="flex absolute bottom-10 left-1.5 z-10 bg-sky-100 hover:scale-110 border-t border-r border-slate-400 hover:bg-slate-300 hover:shadow-lg text-gray-800 font-bold p-1 rounded-tr-md mr-2 w-fit h-fit">
                <svg class="w-4 h-4"
                    viewBox="0 0 1536 1792"
                    xmlns="http://www.w3.org/2000/svg">
                    <path fill="currentColor" d="M1468 380q28 28 48 76t20 88v1152q0 40-28 68t-68 28H96q-40 0-68-28t-28-68V96q0-40 28-68T96 0h896q40 0 88 20t76 48zm-444-244v376h376q-10-29-22-41l-313-313q-12-12-41-22zm384 1528V640H992q-40 0-68-28t-28-68V128H128v1536h1280zm-128-448v320H256v-192l192-192l128 128l384-384zm-832-192q-80 0-136-56t-56-136t56-136t136-56t136 56t56 136t-56 136t-136 56z"/>
                </svg>
                <span
                    v-if="files.length > 0"
                    class="absolute top-0 right-0 inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-red-100 transform translate-x-1/2 -translate-y-1/2 bg-blue-600 rounded-full ring-1 ring-gray-50"
                >
                    {{ files.length }}
                </span>
            </button>
        </div>
        <div v-if="files.length > 0"
            id="files-preview-container"
            class="flex flex-row flex-wrap w-full justify-start gap-1 bg-sky-200 rounded-b-md">
            <div
                v-for="file in files"
                :key="file.name"
                class="flex flex-col h-18 w-18 p-2 relative justify-center items-center content-center"
            >
                <img
                    v-if="file.type.includes('image')"
                    :src="URL.createObjectURL(file)"
                    class="w-fit h-16 object-cover hover:scale-110 shadow-md hover:shadow-lg"
                    :title="`${ file.name }`"
                />
                <iframe v-else-if="file.type.includes('video')"
                    :src="URL.createObjectURL(file)"
                    class="w-fit h-16 object-cover  hover:scale-110 shadow-md hover:shadow-lg"
                    :title="`${ file.name }`"
                    >
                </iframe>
                <embed v-else-if="file.type.includes('audio')"
                    :src="URL.createObjectURL(file)"
                    :title="`${ file.name }`"
                    class="w-fit h-16 object-cover hover:scale-110 shadow-md hover:shadow-lg">
                <!-- if pdf return a miniature preview -->
                <iframe v-else-if="file.type.includes('application/pdf')"
                    :src="URL.createObjectURL(file)"
                    class="w-fit h-16 object-cover">
                </iframe>
                <span v-else
                    :title="`${ file.name }`"
                    class="w-fit h-16 bg-gray-200 text-red-500  hover:scale-110 shadow-md hover:shadow-lg">
                    <svg
                        fill="currentColor"
                        viewBox="0 0 256 256"
                        xmlns="http://www.w3.org/2000/svg">
                        <path d="m212.24 83.76l-56-56A6 6 0 0 0 152 26H56a14 14 0 0 0-14 14v176a14 14 0 0 0 14 14h144a14 14 0 0 0 14-14V88a6 6 0 0 0-1.76-4.24ZM158 46.48L193.52 82H158ZM202 216a2 2 0 0 1-2 2H56a2 2 0 0 1-2-2V40a2 2 0 0 1 2-2h90v50a6 6 0 0 0 6 6h50Zm-45.76-92.24a6 6 0 0 1 0 8.48L136.49 152l19.75 19.76a6 6 0 1 1-8.48 8.48L128 160.49l-19.76 19.75a6 6 0 0 1-8.48-8.48L119.51 152l-19.75-19.76a6 6 0 1 1 8.48-8.48L128 143.51l19.76-19.75a6 6 0 0 1 8.48 0Z"/>
                    </svg>
                </span>
                <button
                    @click.prevent="files = files.filter(f => f.name !== file.name)"
                    class="absolute w-3 h-3 flex items-center justify-center content-center top-1.5 right-1.5 bg-red-50 hover:text-red-500 font-bold rounded-full ring-1 ring-gray-50 hover:ring-red-500 hover:scale-110 transition-all duration-500 ease-in-out"
                >
                    <svg
                        class="w-3 h-3 hover:text-red-500"
                        viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path d="M5 5h2v2H5V5zm4 4H7V7h2v2zm2 2H9V9h2v2zm2 0h-2v2H9v2H7v2H5v2h2v-2h2v-2h2v-2h2v2h2v2h2v2h2v-2h-2v-2h-2v-2h-2v-2zm2-2v2h-2V9h2zm2-2v2h-2V7h2zm0 0V5h2v2h-2z"/>
                    </svg>
                </button>

            </div>
        </div>
        <button
            :disabled="newPost.length === 0"
            @click.prevent="preparePost"
            class="bg-sky-500 hover:bg-blue-400 dark:bg-slate-600 dark:hover:bg-slate-800 text-white font-bold py-2 px-4 rounded-b-md h-9"
        >
            Publicar
        </button>
    </div>
</template>
