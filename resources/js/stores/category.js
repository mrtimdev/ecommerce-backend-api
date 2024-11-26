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

export const useCategory = defineStore('category', () => {
    const form = useForm({
        code: '',
        name: '',
        slug: '',
        image_path: null,
        is_active: true,
        is_save_and_more: false,
        errors: {}
    })
    const isSuccess = ref(false)
    const category = ref(false)
    const categories = ref([])
    const status = ref('')
    const message = ref('')

    
    const storeCategory = () => {
        form.post(route('categories.store'), {
            preserveScroll: true,
            onSuccess: () => {
                isSuccess.value = true
                resetForm()
            },
            onError: () => {
                isSuccess.value = false
            }
        })
    }
    const updateCategory = (item) => {
        form.post(route("categories.update", item.id), {
          preserveScroll: true,
          onSuccess: () => {
            router.visit(route('categories.index'))
            resetForm()
          },
          onError: () => {},
        });
    }
    const setItemValue = (item) => {
        form.id = item.id
        form.code = item.code
        form.name = item.name
        form.slug = item.slug
        form.image_path = null
        form.is_active = item.is_active
    }
    const resetForm = () => {
        form.id = null
        form.code = ''
        form.name = ''
        form.slug = ''
        form.image_path = null
        form.is_active = true
        form.errors = {}
    }

    const deleteCategories = async (item) => {
        var routeName = route('categories.destroy.selected', {
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

    return {
        form,
        category,
        categories,
        storeCategory,
        updateCategory,
        setItemValue,
        resetForm,
        deleteCategories,
        message,
        isSuccess,
        status,
    }
})
