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

export const useCondition = defineStore('condition', () => {
    const form = useForm({
        name: '',
        is_active: true,
    })

    const modal_title = ref('condition.add')
    const event_type = ref('add')

    const checkEvent = (data) => {
        resetForm()
        modal_title.value = data.modal_title
        event_type.value = data.event_type

        if (data.event_type === 'edit') {
            form.id = data.item.id
            form.name = data.item.name
            form.is_active = data.item.is_active
        }
    }
    const submitForm = () => {
        const routeName = event_type.value === 'edit' ? 'conditions.update' : 'conditions.store'
        const routeParams = event_type.value === 'edit' ? form.id : null

        form.post(route(routeName, routeParams), {
            preserveScroll: true,
            onSuccess: () => {
                events.emit('modal:success')
                resetForm()
            },
            onError: () => {
            }
        })
    }

    const resetForm = () => {
        form.id = null
        form.code = ''
        form.name = ''
        form.brand_id = ''
        form.is_active = true
        form.errors = {}
    }

    const deleteConditions = async (item) => {
        var routeName = route('conditions.destroy.selected', {
            ids: item
        })
        form.post(routeName, {
            preserveScroll: true,
            onSuccess: () => {
                events.emit('confirm:cancel')
                events.emit('confirm:success')
            },
        });


    }


    return {
        form,
        modal_title,
        event_type,
        checkEvent,
        submitForm,
        resetForm,
        deleteConditions,
    }
})
