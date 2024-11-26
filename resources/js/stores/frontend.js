import {
    defineStore
} from 'pinia'
import {
    useForm, router
} from '@inertiajs/vue3'
import {
    events
} from '@/events'
import { ref } from 'vue'

export const useFrontend = defineStore('frontend', () => {
    const form = useForm({
        name: '',
        is_active: true,
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
            form.is_active = data.item.is_active
        }
    }
    const submitForm = () => {
        const routeName = event_type.value === 'edit' ? 'page.sliders.update' : 'page.sliders.store'
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

    const deletePageSliders = async (item) => {
        const routeName = route('frontend.page.sliders.destroy.selected', {
            ids: item
        });
    
        axios.get(routeName).then(() => {
            events.emit('confirm:cancel');
            events.emit('confirm:success');
        }).catch((error) => console.error('Error deleting slider:', error));
    };

    const deleteVideos = (item) => {
        // const routeName = route('frontend.page.videos.destroy.selected', {
        //     ids: item
        // });
        // axios.get(routeName, {
        //     headers: {
        //         'X-Inertia': true,
        //       },
        // }).then(() => {
        //     events.emit('confirm:cancel');
        //     events.emit('confirm:success');
        // }).catch((error) => console.error('Error deleting slider:', error));

        var routeName = route('frontend.page.videos.destroy.selected', {
            ids: item
        })
        router.post(routeName, {
            preserveScroll: false,
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
        deletePageSliders,
        deleteVideos
    }
})
