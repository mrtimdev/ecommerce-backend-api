import { ref } from 'vue';
import moment from 'moment';

export default function useHelper() {

    const statusFormat = (status) => {
        if (status === 'approved' || status === 'active' || status === 'available' || parseInt(status) === 1) {
            return `<div class="capitalize text-xs text-center row-status font-semibold inline-block py-1 px-2 rounded bg-success text-white">
                ${status}
            </div>`
        } else if (status === 'rejected' || status === 'inactive' || status === 'booked' || parseInt(status) === 0 || status === "no") {
            return `<div class="capitalize text-xs text-center row-status font-semibold inline-block py-1 px-2 rounded bg-red-600 text-white">
                ${status}
            </div>`
        } else if (status === 'yes' || status === 'sold') {
            return `<div class="capitalize text-xs text-center row-status font-semibold inline-block py-1 px-2 rounded bg-purple-600 text-white">
                ${status}
            </div>`
        } else if (status === 'pending' || status === 'processing') {
            return `<div class="capitalize text-xs text-center row-status font-semibold inline-block py-1 px-2 rounded bg-yellow-600 text-white">
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
                <img src="${src}" alt="Slider Image" class="w-10 h-10 object-cover rounded-lg" />
            </div>
        `
    }
    const countryFlagFormat = (country, show_name = false) => {
        if(!country) {
            return "N/A";
        }
        if(show_name) {
            return `
                <div class="flex gap-2 items-center justify-start">
                    <img src="https://flagcdn.com/w40/${country.code.toLowerCase()}.png"/>
                    <span class="block font-medium mb-0 text-sm text-gray-700 dark:text-white">${country.name}</span>
                </div>
            `
        }
        return `
            <div class="flex gap-2 items-center justify-center">
                <img src="https://flagcdn.com/w40/${country.code.toLowerCase()}.png"/>
            </div>
        `
    }

    const countryFormat = (country) => {
        if(!country) {
            return "N/A";
        }
        return `

            <div class="flex gap-2 items-center justify-center">
                <img src="${route('country.flag.name', { name: `${country.code}.svg` })}" alt="Slider Image" class="w-10 h-10 object-cover rounded-lg" />
                <span class="block font-medium mb-1 text-sm text-gray-700 dark:text-white">${country.name}</span>
            </div>
        `
    }

    const formatMoney = (
        amount,
        currency = 'USD',
        locale = 'en-US',
        decimals = 2,
        digitsOnly = false
    ) => {
        try {
            const options = digitsOnly
            ? {
                style: 'decimal',
                minimumFractionDigits: decimals,
                maximumFractionDigits: decimals,
                }
            : {
                style: 'currency',
                currency,
                minimumFractionDigits: decimals,
                maximumFractionDigits: decimals,
                };

            return new Intl.NumberFormat(locale, options).format(amount);
        } catch (error) {
            console.error('Formatting error:', error);
            return amount.toFixed(decimals);
        }
    }
    

    const generateSlug = (value1 = '', value2 = '') => { 
        const process = (value) => {
            return value
              .toLowerCase() 
              .replace(/[^a-z0-9]+/g, '-') 
              .replace(/^-+|-+$/g, ''); 
        };
    
        const v1 = process(value1);
        const v2 = process(value2);
    
        const combined = `${v1}-${v2}`;
    
        return combined
            .toLowerCase() 
            .replace(/[^a-z0-9]+/g, '-') 
            .replace(/^-+|-+$/g, ''); 
    }

    const extractYouTubeVideoId = (url, is_full_url = false) => {
        if(!url) {
            return ""
        }
        const regex = /(?:https?:\/\/)?(?:www\.)?(?:youtube\.com\/(?:watch\?v=|embed\/|v\/|.*?[?&]v=)|youtu\.be\/)([^&?\/\s]{11})/;
        const match = url.match(regex);
        if(is_full_url) {
            return `<a class="text-blue-400" href="${url}" target="_blank">${(url ?? "")}</a>`
        }
        return match ? match[1] : null;
    }
    const extractFacebookVideoId = (url, is_full_url = false) => {
        if(!url) {
            return ""
        }
        const shareRegex = /https?:\/\/(?:www\.)?web\.facebook\.com\/share\/v\/([^\/?&]+)/;
        const watchRegex = /https?:\/\/(?:www\.)?fb\.watch\/([^\/?&]+)/;
        
        let match = url.match(shareRegex) || url.match(watchRegex);
        if(is_full_url) {
            return `<a class="text-blue-400" href="${url}" target="_blank">${(url ?? "")}</a>`
        }
        return match ? match[1] : null;
    }

    const formatDate = (dateStr, localeFormat = false) => {
        if (localeFormat) {
            return moment(dateStr).format('L LTS');
        } else {
            return moment(dateStr).format('MM/DD/YYYY');
        }
    }


    const formatNumber = (number) => {
        return new Intl.NumberFormat('en-US', { minimumFractionDigits: 0, maximumFractionDigits: 0 }).format(number);
    }
      
    

    return {
        statusFormat,
        imageFormat,
        generateSlug,
        formatMoney,
        extractYouTubeVideoId,
        extractFacebookVideoId,
        countryFormat,
        countryFlagFormat,
        formatDate,
        formatNumber
    }
}
