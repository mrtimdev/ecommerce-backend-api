import { ref } from 'vue'
import { useForm } from '@inertiajs/vue3'

export default function useCarBrands() {
  const brands = ref([]) 
  const isSuccess = ref(false)
  const message = ref('')
  const form = useForm({
    id: null,
    name: '',
    status: 'active',
  })
  
  const deleteBrand = async (item, action = 'delete') => {
    if(action === 'delete-selected') {
      var routeName = route('cars.brands.destroy.selected', {ids: item})
      form.post(routeName, {
        preserveScroll: true,
        onSuccess: () => {
          message.value = 'car.brand.deleted'
        },
        onFinish: (() => {
          console.log('Deleted')
        }),
      });
    } else {
      form.delete(route('cars.brands.destroy', item.id), {
        preserveScroll: true,
        onSuccess: () => {
          message.value = 'car.brand.deleted'
        },
        onFinish: (() => {
          console.log('Deleted')
        }),
      });
    }
    
    
  }

  return {
    deleteBrand,
    message
  }
}
