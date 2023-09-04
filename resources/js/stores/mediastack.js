import { ref, computed } from 'vue';
import axios from 'axios';
import { defineStore } from 'pinia';

export const useMediaStack = defineStore('mediastack',() => {
    const path = 'http://api.mediastack.com/v1/news?access_key=';
    const key = 'f70bca42d5a681653ae4b843988901e6';
    const sources = ref([]);
    const categories = ref([]);
    const countries = ref(['es']);
    const languages = ref('es');
    const limit = ref(10);
    const date = ref({
        from: '',
        to: ''
    });

    const liveNews = ref([]);

    const url = computed(()=>{
        //if sources/categories/countries or date; include it in url, if not, don't include it
        let url = `${path}${key}&languages=${languages.value}&limit=${limit.value}`;
        if(sources.value.length > 0){
            url += `&sources=${sources.value.join(',')}`;
        }
        if(categories.value.length > 0){
            url += `&categories=${categories.value.join(',')}`;
        }
        if(countries.value.length > 0){
            url += `&countries=${countries.value.join(',')}`;
        }
        if(date.value.from != '' && date.value.to != ''){
            url += `&date=${date.value.from},${date.value.to}`;
        }
        if(date.value.from != '' && date.value.to == ''){
            url += `&date=${date.value.from}`;
        }
        return url;
    });

    const toggleCategory = (category) => {
        if(categories.value.includes(category)){
            categories.value = categories.value.filter(cat => cat != category);
        }else{
            categories.value.push(category);
        }
    }

    const toggleSource = (source) => {
        if(sources.value.includes(source)){
            sources.value = sources.value.filter(src => src != source);
        }else{
            sources.value.push(source);
        }
    }

    const fetchNews = () => {
        axios.post(route('news.index'),{
            url: url.value
        }).then(response => {
            console.log(response.data);
            liveNews.value = response.data.data;
        }).catch(error => {
            console.log(error);
        });
    }

    return {
        sources,
        categories,
        date,
        liveNews,
        toggleCategory,
        toggleSource,
        fetchNews
    }
});
