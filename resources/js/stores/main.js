import { defineStore } from "pinia";
import Particles from 'particlesjs'
import { useStorage } from '@vueuse/core'
import { ref } from 'vue'
import { useI18n } from 'vue-i18n';

export const useMainStore = defineStore('mainStore', () => {
    const darkMode = useStorage('darkMode', ref(false))
    document.documentElement.classList.toggle('dark', darkMode.value)
    const { locale } = useI18n();

    const initParticle = () => {
        Particles.init({
            selector: '.particles_background',
            color: ['#DA0463', '#404B69', '#DBEDF3', '#ffffff', '#2a79a2'],
            connectParticles: true,
            minDistance: '100px',
            sizeVariations: 5,
            maxParticles: 200,
            speed: 0.3
        });
    }
    function toggleDarkMode() {
        darkMode.value = !darkMode.value
        document.documentElement.classList.toggle('dark', darkMode.value)
    }


    const setLanguage = (lang) => {
        axios.post('/admin/locale', { locale: lang })
            .then(() => {
                locale.value = lang;
            })
            .catch((err) => {
                locale.value = "en";
            });
    };    

    
    return {
        initParticle,
        darkMode,
        toggleDarkMode,
        locale,
        setLanguage,

    }
})
