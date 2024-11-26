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

export const useColor = defineStore('color', () => {
    const form = useForm({
        name: '',
        description: '',
        code: '',
    })

    const modal_title = ref('fuel_type.add')
    const event_type = ref('add')

    const checkEvent = (data) => {
        resetForm()
        modal_title.value = data.modal_title
        event_type.value = data.event_type

        if (data.event_type === 'edit') {
            form.id = data.item.id
            form.name = data.item.name
            form.description = data.item.description ?? ''
            form.code = data.item.code
        }
        console.log({data})
    }
    const submitForm = () => {
        const routeName = event_type.value === 'edit' ? 'colors.update' : 'colors.store'
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
        form.name = ''
        form.description = ''
        form.code = ''
        form.errors = {}
    }

    const deleteColors = async (item) => {
        var routeName = route('colors.destroy.selected', {
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
        deleteColors,
    }
})
