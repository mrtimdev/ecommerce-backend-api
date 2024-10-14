import {
    defineStore
} from "pinia";
import Particles from 'particlesjs'
import {
    useStorage
} from '@vueuse/core'
import {
    ref
} from 'vue'
import {
    useI18n
} from 'vue-i18n';

export const useMainStore = defineStore('mainStore', () => {
    const darkMode = useStorage('darkMode', ref(false))
    document.documentElement.classList.toggle('dark', darkMode.value)
    // check box for get item value
    const selectedRows = ref([]);
    const checkAll = ref(false);
    const {
        locale
    } = useI18n();

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
        axios.post('/admin/locale', {
                locale: lang
            })
            .then(() => {
                locale.value = lang;
            })
            .catch((err) => {
                locale.value = "en";
            });
    }
    const onCheckAll = () => {
        checkAll.value = !checkAll.value;
        const checkboxes = document.querySelectorAll('.check-row');
        checkboxes.forEach(function (checkbox) {
            checkbox.checked = checkAll.value;
            const id = checkbox.value;
            if (checkAll.value) {
                if (!selectedRows.value.includes(id)) {
                    selectedRows.value.push(id);
                }
            } else {
                selectedRows.value = [];
            }
        });
    }
    const onCheckRow = (is_checked, id) => {
        console.log({is_checked, id})
        if (is_checked) {
            if (!selectedRows.value.includes(id)) {
                selectedRows.value.push(id);
            }
        } else {
            selectedRows.value = selectedRows.value.filter(val => parseInt(val) !== parseInt(id));
            console.log('selectedRows: ', selectedRows.value);
        }
        const totalCheckboxes = document.querySelectorAll('.check-row').length;
        const checkedCheckboxes = document.querySelectorAll('.check-row:checked').length;
        checkAll.value = checkedCheckboxes === totalCheckboxes && totalCheckboxes > 0;
        // console.log('checked row', selectedRows.value);
    }
    const clearSelectedRows = () => {
        selectedRows.value = [];
        checkAll.value = false; 
    }


    return {
        initParticle,
        darkMode,
        toggleDarkMode,
        locale,
        setLanguage,
        onCheckAll,
        onCheckRow,
        selectedRows,
        checkAll,
        clearSelectedRows
    }
})
