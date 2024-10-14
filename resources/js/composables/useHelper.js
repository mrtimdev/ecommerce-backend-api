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
        const image_path = src ? `/storage/${src}` : `/assets/images/no-image.jpg`
        return `
            <div class="image flex items-center justify-center">
                <img src="${image_path}" alt="Slider Image" class="w-10 h-10 object-cover rounded-lg" />
            </div>
        `
    }

    const generateSlug = (value) => {
        return value
          .toLowerCase() // Convert to lowercase
          .replace(/[^a-z0-9]+/g, '-') // Replace non-alphanumeric characters with hyphens
          .replace(/^-+|-+$/g, ''); // Remove leading/trailing hyphens
    }

    return {
        statusFormat,
        imageFormat,
        generateSlug
    }
}
