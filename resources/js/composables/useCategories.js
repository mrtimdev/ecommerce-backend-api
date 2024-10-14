import { ref } from 'vue'
import { useForm } from '@inertiajs/vue3'

export default function useCarCategories() {
  const categories = ref([]) // Holds all categories
  const showModal = ref(false)
  const message = ref('')
  const showDeleteModal = ref(false)
  const isEdit = ref(false)
  const isSuccess = ref(false)
  const form = useForm({
    id: null,
    name: '',
    status: 1,
  })

  // Fetch categories (could also be server-side rendered)
  const fetchCategories = async () => {
    await form.get(route('cars.categories.index'), {
      onSuccess: (response) => {
        categories.value = response.props.categories
        showModal.value = false
      },
    })
  }

  // Open Add Modal
  const openAddModal = () => {
    form.reset()
    isEdit.value = false
    showModal.value = true
  }

  // Open Edit Modal
  const openEditModal = (category) => {
    form.id = category.id
    form.name = category.name
    form.status = category.status
    isEdit.value = true
    showModal.value = true
  }

  // Handle Form Submit (Add or Edit)
  const handleSubmit = () => {
    isSuccess.value = false
    form.post(route('cars.categories.store'), {
      onSuccess: () => {
        form.reset()
        isSuccess.value = true
      },
      onError: () => {
        isSuccess.value = false
      }
    })
  }

  // Open Delete Confirmation Modal
  const confirmDelete = (category) => {
    form.id = category.id
    form.name = category.name
    showDeleteModal.value = true
  }

  // Delete Category
  
  const deleteCategory = async (item, action = 'delete') => {
    if(action === 'delete-selected') {
      var routeName = route('categories.destroy.selected', {ids: item})
      form.post(routeName, {
        preserveScroll: true,
        onSuccess: () => {
          message.value = 'category.deleted'
        },
        onFinish: (() => {
          console.log('Deleted')
        }),
      });
    } else {
      form.delete(route('categories.destroy', item.id), {
        preserveScroll: true,
        onSuccess: () => {
          message.value = 'category.deleted'
        },
        onFinish: (() => {
          console.log('Deleted')
        }),
      });
    }
    
    
  };

  // Close Add/Edit Modal
  const closeModal = () => {
    showModal.value = false
    form.reset()
  }

  // Close Delete Modal
  const closeDeleteModal = () => {
    showDeleteModal.value = false
    form.reset()
  }

  return {
    categories,
    fetchCategories,
    showModal,
    showDeleteModal,
    isEdit,
    isSuccess,
    form,
    openAddModal,
    openEditModal,
    handleSubmit,
    confirmDelete,
    deleteCategory,
    closeModal,
    closeDeleteModal,
  }
}
