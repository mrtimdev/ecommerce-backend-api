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

export const useModel = defineStore('model', () => {
    const form = useForm({
        code: '',
        name: '',
        brand_id: '',
        is_active: true,
        is_save_and_more: false,
    })

    const modal_title = ref('model.add')
    const event_type = ref('add')
    const isSuccess = ref(false)
    const brands = ref(false)
    const message = ref('')

    const checkEvent = (data) => {
        clearForm()
        modal_title.value = data.modal_title
        event_type.value = data.event_type

        if (data.event_type === 'edit') {
            form.id = data.item.id
            form.code = data.item.code
            form.name = data.item.name
            form.brand_id = data.item.brand_id
            form.is_active = data.item.is_active
        }
        getBrands()
    }
    const submit = () => {
        const routeName = event_type.value === 'edit' ? 'models.update' : 'models.store'
        const routeParams = event_type.value === 'edit' ? form.id : null

        form.post(route(routeName, routeParams), {
            preserveScroll: true,
            onSuccess: () => {
                events.emit('modal:success')
                isSuccess.value = true
                clearForm()
            },
            onError: () => {
                isSuccess.value = false
            }
        })
    }

    const clearForm = () => {
        form.id = null
        form.code = ''
        form.name = ''
        form.brand_id = ''
        form.is_active = true
        form.errors = {}
    }

    const deleteModels = async (item) => {
        var routeName = route('models.destroy.selected', {
            ids: item
        })
        form.post(routeName, {
            preserveScroll: true,
            onSuccess: () => {
                events.emit('confirm:cancel')
                events.emit('confirm:success')
                message.value = 'model.deleted'
            },
        });


    }


    const getBrands = async () => {
        try {
            const response = await axios.get(route('brands.all'))
            brands.value = response.data.brands
        } catch (error) {
            console.error('Error fetching brands:', error)
        }
    }

    return {
        form,
        brands,
        getBrands,
        modal_title,
        event_type,
        checkEvent,
        submit,
        isSuccess,
        clearForm,
        deleteModels,
        message,
    }
})
