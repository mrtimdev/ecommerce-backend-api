<template>
  <div class="relative">
    <label v-if="label" :for="id" class="form-label mb-2">{{ label }}</label>
    <select
      :id="id"
      :value="modelValue"
      @change="$emit('update:modelValue', $event.target.value)"
      :class="[
        'block w-full px-4 py-2.5 text-base',
        'border border-gray-300 rounded-lg',
        'focus:ring-2 focus:ring-purple-500 focus:border-purple-500',
        'dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white',
        'dark:focus:ring-purple-500 dark:focus:border-purple-500',
        'appearance-none pr-8 transition duration-200 ease-in-out',
        // Add more classes based on props or state if needed
      ]"
      :placeholder="placeholder"
      :required="required"
      :disabled="disabled"
      ref="selectInput"
    >
      <option value="" disabled selected v-if="placeholder">{{ placeholder }}</option>
      <slot />
    </select>
    <div
      class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700 dark:text-gray-300"
      :class="{ 'top-0': label, 'top-auto': !label }"
      style="top: 50%; transform: translateY(-50%)"
    >
      <svg
        class="fill-current h-4 w-4"
        xmlns="http://www.w3.org/2000/svg"
        viewBox="0 0 20 20"
      >
        <path
          d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"
        />
      </svg>
    </div>
  </div>
</template>

<script setup>
import { onMounted, ref } from "vue";

const props = defineProps({
  id: {
    type: String,
    default: () => `select-input-${Math.random().toString(36).substring(2, 9)}`, // Unique ID
  },
  modelValue: {
    type: [String, Number, Array, Boolean],
    default: "",
  },
  label: {
    type: String,
    default: "",
  },
  placeholder: {
    type: String,
    default: "",
  },
  required: {
    type: Boolean,
    default: false,
  },
  disabled: {
    type: Boolean,
    default: false,
  },
});

defineEmits(["update:modelValue"]);

const selectInput = ref(null);

onMounted(() => {
  if (selectInput.value.hasAttribute("autofocus")) {
    selectInput.value.focus();
  }
});

const focus = () => {
  selectInput.value.focus();
};

defineExpose({ focus });
</script>

<style scoped>
/* Scoped styles, or rely purely on Tailwind classes */
.form-label {
  @apply block text-sm font-medium text-gray-700 dark:text-gray-300;
}
</style>
