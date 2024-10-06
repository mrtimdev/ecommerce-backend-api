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
    };

    return {
        statusFormat
    }
}
