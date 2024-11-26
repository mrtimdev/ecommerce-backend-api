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

export const useSetting = defineStore('setting', () => {
    
    const countries = ref([])

    const getCountries = async () => {

        return new Promise((resolve, reject) => {
            axios.get(route('settings.countries.all'))
                .then((response) => {
                    resolve(response)
                    countries.value = response.data.countries
                }).catch((error) => {
                    reject(error)
                    console.error('Error fetching countries:', error)
                }) 
        })


        
    }

    return {
        countries,
        getCountries,
    }
})
