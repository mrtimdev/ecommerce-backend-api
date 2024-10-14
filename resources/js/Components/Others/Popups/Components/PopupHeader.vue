<template>
    <div class="popup-header flex items-center justify-between px-6 pt-6 pb-6">
        <div class="flex items-center">
            <div class="mr-3">
                <i size="18" class="fa fa-key" />
            </div>

            <b class="text-base font-bold">
                {{ title }}
            </b>
        </div>
        <div v-if="showCloseBottun != false" @click="close" class="-m-3 cursor-pointer p-3">
            <x-icon size="14" class="hover-text-theme vue-feather" />
        </div>
    </div>
</template>

<script>
    const props = defineProps({
        title: {
            type: String,
            default: false,
        },
        icon: {
            type: String,
            default: false,
        },
        showCloseBottun: {
            type: Boolean,
            default: true,
        },
    })
    const emit = defineEmits(['close'])

    const close = () => {
        emit('close')
    }
</script>
