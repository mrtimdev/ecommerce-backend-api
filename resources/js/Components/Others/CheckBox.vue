<template>
        <input type="checkbox" :checked=" modelValue " @change="onChangeHandler"
            class="w-4 h-4 text-purple-600 bg-gray-100 border-gray-300 rounded focus:ring-purple-500 dark:focus:ring-purple-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600" />
</template>

<script setup>
import { ref, computed } from 'vue';

// Props
const props = defineProps({
    disabled: {
        type: Boolean,
        default: false
    },
    modelValue: {
        type: Boolean,
        required: true
    }

});

const emit = defineEmits(['update:modelValue', 'change']);

const onChangeHandler = (event) => {
  const isCheck = event.target.checked;
  const value = event.target.value;
  emit('update:modelValue', isCheck);  
  console.log({isCheck, value}); 
};
</script>

<style scoped>

</style>