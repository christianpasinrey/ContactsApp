<script setup>
    import { onMounted } from 'vue';
    const props = defineProps(['body', 'files']);

    const handleFilePath = (file) => {
        return `storage/files/${file.path.replace('public/files/', '')}`
    };
    onMounted(() => {
        console.log(props.body);
    });
</script>
<template>
    <div class="flex flex-row py-1 justify-start px-1 rounded-lg shadow-md bg-slate-200 h-fit w-full hover:bg-slate-300 transition-all duration-500 ease-in-out">
        <div class="timeline-post-body-wrapper">
            <p v-html="body"></p>
            <!-- preview of files -->
            <div class="flex flex-row flex-wrap justify-start gap-2 mt-6 relative">
                <small
                    v-if="props.files.some((f)=>f.type==='image')"
                    class="text-xs font-bold text-gray-500 absolute -bottom-5 left-0"
                >
                    Imágenes adjuntas
                </small>
                <div
                    v-for="file in props.files"
                    :key="`file-${file.id}`"
                    class="rounded-md overflow-hidden shadow-md"
                >
                    <img
                        v-if="file.type === 'image'"
                        class="w-32 h-32 object-cover"
                        :src="handleFilePath(file)"
                        :alt="file.name"
                    />
                </div>
            </div>
        </div>
    </div>
</template>
