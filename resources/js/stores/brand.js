import {
    defineStore
} from 'pinia'
import {
    useForm,
    router
} from '@inertiajs/vue3'
import {
    events
} from '@/events'
import { ref } from 'vue'

export const useBrand = defineStore('brand', () => {
    const deleteBrands = async (item) => {
        const routeName = route('brands.destroy.selected', { ids: item });
        axios.post(routeName, {}, {
            headers: { 'X-Requested-With': 'XMLHttpRequest' },
            withCredentials: true,
        }).then((response) => {
            events.emit('confirm:cancel');
            events.emit('confirm:success');
        }).catch((error) => {
            events.emit('confirm:cancel');
        });
    };

    return {
        deleteBrands,
    }
})
