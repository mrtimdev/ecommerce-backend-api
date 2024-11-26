import {
    defineStore
} from 'pinia'
import {
    useForm
} from '@inertiajs/vue3'
import {
    events
} from '@/events'
import { ref } from 'vue'

export const useCar = defineStore('car', () => {
    const car = ref(false)
    const galleries = ref(false)
    const loading = ref(true)
    const deleteCars = async (ids) => {
        // NProgress.start();
        const routeName = route('cars.destroy.selected', { ids: ids });
        axios.post(routeName).then((res) => {
            console.log({res})
            events.emit('toaster', {
                type: 'success',
                action: 'delete',
                message: `${t('item.count', ids.length)} ${t('successfully_deleted')}`,
            })
            events.emit('confirm:cancel');
            events.emit('confirm:success');
            // NProgress.done();
            // NProgress.remove();
            
        }).catch((error) => {
            events.emit('confirm:cancel');
        });
    };

    const getCarById = async (id) => {
        loading.value = true
        return await axios.get(route('cars.show', id))
        .then((res) => {
            car.value = res.data.data
            loading.value = false
        }).catch((error) => {
            events.emit('confirm:cancel');
        });
    }

    const getCarGalleries = async (id) => {
        loading.value = true
        return await axios.get(route('cars.galleries', id))
        .then((res) => {
            galleries.value = res.data.data
            loading.value = false
        }).catch((error) => {
            events.emit('confirm:cancel');
        });
    }

    const clearItem = () => {
        car.value = false
        loading.value = false
        galleries.value = false
    }

    

    return {
        deleteCars,
        getCarById,
        car,
        clearItem,
        loading,
        getCarGalleries,
        galleries
    }
})
