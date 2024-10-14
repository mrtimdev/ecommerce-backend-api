import { ref } from 'vue'
import { useForm } from '@inertiajs/vue3'

export default function useCarFuelTypes() {
  const fuelTypes = ref([]) 
  const isSuccess = ref(false)
  const message = ref('')
  const form = useForm({
    id: null,
    name: '',
    status: 'active',
  })
  
  const deleteFuelType = async (item, action = 'delete') => {
    if(action === 'delete-selected') {
      var routeName = route('fuelTypes.destroy.selected', {ids: item})
      form.post(routeName, {
        preserveScroll: true,
        onSuccess: () => {
          message.value = 'condition.deleted'
        },
        onFinish: (() => {
          console.log('Deleted')
        }),
      });
    } else {
      form.delete(route('fuelTypes.destroy', item.id), {
        preserveScroll: true,
        onSuccess: () => {
          message.value = 'condition.deleted'
        },
        onFinish: (() => {
          console.log('Deleted')
        }),
      });
    }
    
    
  }

  return {
    deleteFuelType,
    message
  }
}
