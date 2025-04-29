<template>
  <div
    class="upload-container gap-6 mb-6 relative border-2 border-dashed border-gray-400 rounded-lg"
    @dragover.prevent="toggleDrag(true)"
    @dragleave.prevent="toggleDrag(false)"
    @drop.prevent="handleDrop"
    :class="{ 'drag-over border-purple-400': isDragging }"
  >
    <label
      :for="target_input"
      :style="{
        'background-image': selectedImage
          ? `url(${selectedImage})`
          : selectedFile
          ? `url(/storage/${selectedFile})`
          : '',
        'background-size': 'contain',
        'background-position': 'center',
        'background-repeat': 'no-repeat',
      }"
      :class="{ 'h-[13rem] selected-image': selectedImage, 'h-52': !selectedImage }"
      class="upload-label dark:bg-meta-4 dark:text-white text-gray-500 font-semibold text-base rounded flex flex-col items-center justify-center cursor-pointer mx-auto"
    >
      <template v-if="!selectedImage">
        <svg
          xmlns="http://www.w3.org/2000/svg"
          class="w-[100px] mb-2 fill-gray-500"
          viewBox="0 0 32 32"
        >
          <path
            d="M23.75 11.044a7.99 7.99 0 0 0-15.5-.009A8 8 0 0 0 9 27h3a1 1 0 0 0 0-2H9a6 6 0 0 1-.035-12 1.038 1.038 0 0 0 1.1-.854 5.991 5.991 0 0 1 11.862 0A1.08 1.08 0 0 0 23 13a6 6 0 0 1 0 12h-3a1 1 0 0 0 0 2h3a8 8 0 0 0 .75-15.956z"
          />
          <path
            d="M20.293 19.707a1 1 0 0 0 1.414-1.414l-5-5a1 1 0 0 0-1.414 0l-5 5a1 1 0 0 0 1.414 1.414L15 16.414V29a1 1 0 0 0 2 0V16.414z"
          />
        </svg>
        <span>{{ t("drop_file_here") }} {{ t("or") }} {{ t("browse_file") }}</span>
      </template>
      <span v-if="multiple && image_path">{{ t("multiple_files_selected") }}</span>

      <input
        type="file"
        :id="target_input"
        :multiple="multiple"
        @change="handleFileChange"
        class="hidden"
        accept=".png, .jpg, .jpeg"
      />

      <p class="text-xs font-medium text-gray-400 mt-2">
        PNG, JPG, JPEG and 2M are allowed.
      </p>
    </label>

    <button
      v-if="image_path || selectedImage"
      @click="removeImage"
      type="button"
      class="remove-button absolute right-0 top-0 z-2"
    >
      <svg
        class="w-3 h-3"
        xmlns="http://www.w3.org/2000/svg"
        fill="none"
        viewBox="0 0 14 14"
      >
        <path
          stroke="currentColor"
          stroke-linecap="round"
          stroke-linejoin="round"
          stroke-width="2"
          d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"
        />
      </svg>
    </button>
  </div>
</template>

<script setup>
import { ref, watch } from "vue";
import { useI18n } from "vue-i18n";

import { events } from "@/events";

// Props
const props = defineProps({
  multiple: { type: Boolean, default: false },
  modelValue: { type: [File, Array], default: null },
  selectedFile: { type: String, default: null },
  target_input: { type: String, default: "upload-input" },
});

// Emits
const emit = defineEmits(["update:modelValue"]);

// Reactive state
const image_path = ref(props.modelValue);
const selectedImage = ref(null);
const isDragging = ref(false);

// i18n instance
const { t } = useI18n();

// Handle file input changes
const handleFileChange = (event) => {
  const files = Array.from(event.target.files);

  if (!props.multiple) {
    const file = files[0];
    image_path.value = file;
    if (file) {
      selectedImage.value = URL.createObjectURL(file);
    }

    emit("update:modelValue", file);
  } else {
    image_path.value = files;
    selectedImage.value = null;
    emit("update:modelValue", files);
  }
};

// Drag-and-drop handlers
const toggleDrag = (state) => (isDragging.value = state);

const handleDrop = (event) => {
  handleFileChange({ target: { files: event.dataTransfer.files } });
  toggleDrag(false);
};

// Watch for modelValue changes
watch(
  () => props.modelValue,
  (newValue) => (image_path.value = newValue)
);

// Remove selected image
const removeImage = () => {
  image_path.value = null;
  selectedImage.value = null;
};

events.on("clear-selected-file", () => {
  removeImage();
});
</script>

<style scoped>
.upload-container {
  transition: background-color 0.3s ease-in-out;
}
.upload-label {
  transition: height 0.3s, background-color 0.3s;
}
.drag-over {
  background-color: rgba(0, 0, 0, 0.05);
}
.remove-button {
  color: #888;
  background: transparent;
  border: none;
  cursor: pointer;
  padding: 0;
  width: 24px;
  height: 24px;
  display: flex;
  align-items: center;
  justify-content: center;
}
</style>
