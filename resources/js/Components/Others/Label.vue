<template>
    <label :for="for_id" class="block mb-2 text-sm font-medium text-gray-700 dark:text-white">
        <slot name="default"></slot>
        <span v-if="isRequired" class="text-rose-600">*</span>
    </label>
</template>
<script setup>
    const props = defineProps({
        for_id: {
            type: String,
            default: 'target_input_id',
            required: true
        },
         
        isRequired: {
            type: Boolean,
            default: false,
            required: false
        }
    })
</script>