import { ref } from 'vue'
import { router } from '@inertiajs/vue3'

export default function useCarBrands() {
  const brands = ref([]) 
  const isSuccess = ref(false)
  const message = ref('')
  
  const deleteSlider = async (item, action = 'delete') => {
    if(action === 'delete-selected') {
      var routeName = route('frontend.homepage.sliders.destroy.selected', {ids: item})
      router.get(routeName, {
        preserveScroll: true,
        onSuccess: () => {
          message.value = 'homepage.slider.deleted'
        },
        onFinish: (() => {
          console.log('Deleted')
        })
      });
    } else {
      router.delete(route('frontend.homepage.sliders.destroy', item.id), {
        preserveScroll: true,
        onSuccess: () => {
          message.value = 'homepage.slider.deleted'
        },
        onFinish: (() => {
          console.log('Deleted')
        }),
      });
    }
    
    
  }

  return {
    deleteSlider,
    message
  }
}
