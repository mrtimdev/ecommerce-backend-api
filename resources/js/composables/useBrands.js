import { ref } from 'vue'
import { router } from '@inertiajs/vue3'

export default function useCarBrands() {
  const brands = ref([]) 
  const isSuccess = ref(false)
  const message = ref('')  
  const deleteBrand = async (item, action = 'delete') => {
    if(action === 'delete-selected') {
      var routeName = route('brands.destroy.selected', {ids: item})
      router.post(routeName, {
        preserveScroll: true,
        onSuccess: () => {
          message.value = 'brand.deleted'
        },
        onFinish: (() => {
          console.log('Deleted')
        }),
      });
    } else {
      router.delete(route('brands.destroy', item.id), {
        preserveScroll: true,
        onSuccess: () => {
          message.value = 'brand.deleted'
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
