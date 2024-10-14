<script setup>
import { onMounted, ref } from 'vue';

const model = defineModel({
    type: String,
    required: true,
});

const textarea = ref(null);

onMounted(() => {
    if (textarea.value.hasAttribute('autofocus')) {
        textarea.value.focus();
    }
});

defineExpose({ focus: () => textarea.value.focus() });
</script>

<template>
    <textarea
      rows="4"
      class="w-full p-2 border border-gray-300 rounded focus:ring-purple-500 
            dark:bg-boxdark-1 dark:border-gray-600 dark:focus:ring-purple-600 
            dark:ring-offset-gray-800"
        v-model="model"
        ref="textarea"
    >
  </textarea>
</template>
