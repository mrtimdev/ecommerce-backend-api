import { ref } from 'vue';

export default function useHelper() {

    const statusFormat = (status) => {
        if (status === 'active') {
            return `<div class="capitalize text-xs text-center row-status font-semibold inline-block py-1 px-2 rounded bg-success text-white">
                ${status}
            </div>`
        } else if (status === 'inactive') {
            return `<div class="capitalize text-xs text-center row-status font-semibold inline-block py-1 px-2 rounded bg-red-600 text-white">
                ${status}
            </div>`
        } else {
            return `<div class="capitalize text-xs text-center row-status font-semibold inline-block py-1 px-2 rounded-full bg-gray-600 text-white">
                ${status}
            </div>`
        }
    }

    const imageFormat = (src) => {

        return `
            <div class="image flex items-center justify-center">
                <img src="/storage/${src}" alt="Slider Image" class="w-10 h-10 object-cover rounded-lg" />
            </div>
        `
    }

    return {
        statusFormat,
        imageFormat
    }
}
