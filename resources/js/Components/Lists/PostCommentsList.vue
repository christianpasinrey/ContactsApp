<script setup>
    import { ref } from 'vue';
    import {format} from 'date-fns';
    defineProps(['comments']);
    defineEmits(['commented']);

    const newComment = ref('');

    function shortName(name) {
        return name.split(' ').map((n)=>n[0]).join('');
    }

    const generateTitleHtmlTooltip = (e,name) => {
        const tooltip = document.createElement('div');
        tooltip.classList.add('tooltip','text-xs','absolute','z-[100]','font-semibold','text-gray-100','bg-gray-800','px-2','py-1','rounded-md');
        tooltip.innerHTML = name;
        tooltip.style.top = `${e.clientY}px`;
        tooltip.style.left = `${e.clientX}px`;
        document.body.appendChild(tooltip);
        console.log(tooltip);
    }

    const hideTitleHtmlTooltip = () => {
        const tooltip = document.querySelector('.tooltip');
        if(tooltip){
            document.body.removeChild(tooltip);
        }
    }
</script>
<template>
    <Transition name="rollFromTop">
        <article>
            <section name="new-comment">
                <div class="flex flex-col w-full">
                    <form @submit.prevent="$emit('commented', newComment)">
                        <textarea
                        v-model="newComment"
                        placeholder="Escribe un comentario..."
                        rows="3"
                        class="w-full border-2 border-gray-300 rounded-lg p-2 focus:outline-none focus:border-sky-500 transition-all duration-500 ease-in-out">
                        </textarea>
                        <div
                            class="flex w-full justify-end content-end items-end align-middle">
                            <button type="submit"
                                class="w-fit bg-sky-500 text-gray-100 font-semibold rounded-lg p-2 mt-2 hover:bg-sky-600 transition-all duration-500 ease-in-out"
                            >
                                Enviar comentario
                            </button>
                        </div>
                    </form>
                </div>
            </section>
            <section name="comment-list" class="pb-2">
                <div class="flex flex-row my-1.5 items-center justify-start gap-6">
                    <h3 class="text-sm uppercase font-bold text-gray-800">Comentarios</h3>
                    <span class="text-sm font-semibold text-gray-500">{{ comments.length }}</span>
                </div>
                <hr>
                <div class="flex flex-col w-full">
                    <ol>
                        <li v-for="comment in comments" class="relative bg-slate-200 rounded-md px-2">
                            <div class="flex px-4 py-2 gap-3">
                                <span
                                    @mouseenter="generateTitleHtmlTooltip($event,comment.user.name)"
                                    @mouseleave="hideTitleHtmlTooltip"
                                    class="rounded-full ring-1 ring-slate-300 font-bold px-1.5 bg-slate-50 relative">
                                    {{ shortName(comment.user.name) }}
                                </span>
                                <p class="text-gray-800">{{ comment.body }}</p>
                            </div>
                            <span class="absolute bottom-0 lef-0 w-full flex p-1 justify-end text-gray-400 text-sm font-semibold">{{ format(new Date(comment.created_at),'dd/MM/yyyy') }}</span>
                        </li>
                    </ol>
                </div>
            </section>
        </article>
    </Transition>
</template>
<style scoped>
    ol{
        list-style: none;
        padding: 0;
        margin:0;
    }
    li{
        margin: 0;
        padding: 0;
    }
    hr{
        border: 1px solid #909496;
    }

    @keyframes rollFromTop {
        0% {
            transform: translateY(-100%);
        }
        100% {
            transform: translateY(0%);
        }
    }

    .rollFromTop-enter-active {
        animation: rollFromTop 0.7s ease-in-out;
    }

    .rollFromTop-leave-active {
        animation: rollFromTop 0.7s ease-in-out reverse;
    }

    .rollFromTop-enter,
    .rollFromTop-leave-to {
        transform: translateY(-100%);
    }

</style>
