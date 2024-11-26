<script setup>
import { onMounted, ref, onBeforeUnmount } from 'vue';
import 'summernote/dist/summernote-lite.css'
import 'summernote/dist/summernote-lite.js'

const emit = defineEmits(['update:model']);
const model = defineModel({
    type: String,
    required: true,
});


const editor = ref(null);
const handleContentChange = (content) => {
    console.log('Content changed:', content)
    emit('update:model', content)
}

onMounted(() => {
    if (editor.value.hasAttribute('autofocus')) {
        editor.value.focus();
    }
    $(document).ready(function() {
      $(editor.value).summernote({
        placeholder: 'Enter text here...',
        callbacks: {
            onChange: handleContentChange
        }
      });
    });
});

defineExpose({ focus: () => editor.value.focus() });

onBeforeUnmount(() => {
  $(editor.value).summernote('destroy')
})
</script>

<template>
    <textarea
      rows="4"
      class="w-full p-2 border border-gray-300 rounded focus:ring-purple-500 
            dark:bg-boxdark-1 dark:border-gray-600 dark:focus:ring-purple-600 
            dark:ring-offset-gray-800"
        v-model="model"
        ref="editor"
    >
  </textarea>
</template>
