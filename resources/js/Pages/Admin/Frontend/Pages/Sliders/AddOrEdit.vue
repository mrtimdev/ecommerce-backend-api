<template>
  <Teleport to="body">
    <modal
      size="md"
      :show="isVisible"
      :show-footer="false"
      :show-confirm-button="true"
      button-confirm-label="save"
      @close="closeModal"
    >
      <template #title>
        {{ t(modal_title) }}
      </template>
      <template #body>
        <form @submit.prevent="sliderFormSubmit" enctype="multipart/form-data">
          <div class="p-4 md:p-5">
            <div class="grid gap-6 mb-6 md:grid-cols-2">
              <div>
                <Label
                  :isRequired="true"
                  for_id="title"
                  class="block mb-2 text-sm font-medium text-gray-700 dark:text-white"
                >
                  {{ $t("title") }}</Label
                >
                <input
                  type="text"
                  id="title"
                  ref="title"
                  v-model="form.title"
                  class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-purple-500 focus:border-purple-500 block w-full p-2.5 dark:bg-boxdark-1 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-purple-500 dark:focus:border-purple-500 dark:bg-tim-11"
                  :placeholder="$t('slider.title')"
                />
                <InputError :message="form.errors.title" class="mt-2" />
              </div>
              <div>
                <Label
                  for_id="is_active"
                  class="block mb-2 text-sm font-medium text-gray-900 dark:text-white"
                >
                  {{ $t("select") }} {{ $t("status") }}
                </Label>
                <select
                  id="is_active"
                  v-model="form.is_active"
                  class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-purple-500 focus:border-purple-500 block w-full p-2.5 dark:bg-boxdark-1 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-purple-500 dark:focus:border-purple-500 dark:bg-tim-11"
                >
                  <option disabled>{{ $t("select") }} {{ $t("status") }}</option>
                  <option :value="true">{{ $t("active") }}</option>
                  <option :value="false">{{ $t("inactive") }}</option>
                </select>
                <InputError :message="form.errors.is_active" class="mt-2" />
              </div>
            </div>
            <div class="mb-6">
              <Label for_id="link" class="block font-medium mb-1">{{ $t("link") }}</Label>
              <input
                type="text"
                id="link"
                ref="link"
                v-model="form.link"
                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-purple-500 focus:border-purple-500 block w-full p-2.5 dark:bg-boxdark-1 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-purple-500 dark:focus:border-purple-500 dark:bg-tim-11"
                placeholder="Ex: https://reachautoimport.com/services"
              />
              <InputError :message="form.errors.link" class="mt-2" />
            </div>
            <div class="mb-6">
              <Label for_id="description" class="block font-medium mb-1">{{
                $t("description")
              }}</Label>
              <Textarea id="description" v-model="form.description"></Textarea>
              <InputError :message="form.errors.description" class="mt-2" />
            </div>
            <div class="grid gap-6 mb-6 md:grid-cols-2">
              <div>
                <Label
                  :isRequired="true"
                  for_id="image_path"
                  class="block mb-2 text-sm font-medium text-gray-900 dark:text-white"
                  >{{ $t("image") }} (Desktop)</Label
                >
                <FileUpload
                  v-model="form.image_path"
                  target_input="image_path"
                  :selectedFile="selectedImage"
                />
                <InputError :message="form.errors.image_path" class="mt-2" />
              </div>

              <div>
                <Label
                  :isRequired="true"
                  for_id="image_path_2"
                  class="block mb-2 text-sm font-medium text-gray-900 dark:text-white"
                  >{{ $t("image") }} (Mobile)</Label
                >
                <FileUpload
                  v-model="form.image_path_2"
                  target_input="image_path_2"
                  :selectedFile="selectedImage2"
                />
                <InputError :message="form.errors.image_path_2" class="mt-2" />
              </div>
            </div>
          </div>
          <div
            class="modal-footer p-4 md:p-5 border-t border-gray-300 dark:border-gray-400"
          >
            <div class="flex justify-center gap-5 items-center">
              <button
                @click="closeModal"
                type="button"
                class="w-full focus:outline-none text-white bg-rose-700 hover:bg-rose-800 focus:ring-4 focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5 dark:bg-rose-600 dark:hover:bg-rose-700 dark:focus:ring-rose-900"
              >
                {{ $t("close") }}
              </button>
              <button
                class="w-full focus:outline-none text-white bg-purple-700 hover:bg-purple-800 focus:ring-4 focus:ring-purple-300 font-medium rounded-lg text-sm px-5 py-2.5 dark:bg-purple-600 dark:hover:bg-purple-700 dark:focus:ring-purple-900"
              >
                {{ $t("save") }}
              </button>
            </div>
          </div>
        </form>
      </template>
    </modal>
  </Teleport>
</template>

<script setup>
import Label from "@/Components/Others/Label.vue";
import FileUpload from "@/Components/Others/FileUpload.vue";
import Textarea from "@/Components/Others/Textarea.vue";
import InputError from "@/Components/InputError.vue";
import { ref, nextTick } from "vue";
import { useForm } from "@inertiajs/vue3";
import { useI18n } from "vue-i18n";
const { t } = useI18n();
import { events } from "@/events";

const emit = defineEmits(["open", "close", "success"]);

const isVisible = ref(false);
const modal_title = ref("slider.add");
const event_type = ref("add");

events.on("modal:success", () => {
  isVisible.value = false;
});

const selectedImage = ref(null);
const selectedImage2 = ref(null);
const title = ref(null);
const form = useForm({
  id: null,
  title: "",
  description: "",
  is_active: true,
  image_path: null,
  image_path_2: null,
  link: "",
});

const closeModal = () => {
  isVisible.value = false;
  form.title = "";
  form.description = "";
  form.link = "";
  form.is_active = true;
  form.image_path = null;
  form.image_path_2 = null;
  selectedImage.value = null;
  selectedImage2.value = null;
  events.emit("modal:close");
};
events.on("modal:open", (data) => {
  modal_title.value = data.modal_title || "slider.add";
  event_type.value = data.event_type;
  isVisible.value = true;
  nextTick(() => {
    title.value.focus();
  });
  form.errors = {};
  if (event_type.value === "edit" && data.item) {
    nextTick(() => {
      form.id = data.item.id;
      form.title = data.item.title;
      form.description = data.item.description ?? "";
      form.link = data.item.link ?? "";
      form.is_active = data.item.is_active;
      selectedImage.value = data.item.image_path;
      selectedImage2.value = data.item.image_path_2;
      form.image_path = null;
      form.image_path_2 = null;
    });
  } else {
    form.title = "";
    form.description = "";
    form.link = "";
    form.is_active = true;
    form.image_path = null;
    selectedImage.value = null;
  }
});

const props = defineProps({
  showModal: {
    type: Boolean,
    default: false,
  },
});

const sliderFormSubmit = () => {
  if (event_type.value === "edit") {
    form.post(route("frontend.page.sliders.update", form), {
      onSuccess: () => {
        nextTick(() => {
          form.reset();
          isVisible.value = false;
          events.emit("modal:success");
        });
      },
      onError: () => {
        isVisible.value = true;
      },
    });
  } else {
    form.post(route("frontend.page.sliders.store"), {
      onSuccess: () => {
        nextTick(() => {
          form.title = "";
          form.description = "";
          form.link = "";
          form.is_active = true;
          form.image_path = null;
          form.image_path_2 = null;
          selectedImage.value = null;
          selectedImage2.value = null;
          isVisible.value = false;
          events.emit("modal:success");
        });
      },
      onError: () => {
        isVisible.value = true;
      },
    });
  }
};

events.on("delete-items", (ids) => {
  var routeName = route("frontend.page.sliders.destroy.selected", {
    ids: ids,
  });
  form.post(routeName, {
    preserveScroll: true,
    onSuccess: () => {
      events.emit("confirm:cancel");
      events.emit("confirm:success");
    },
  });
});
</script>

<style scoped>
[for="upload_file"] {
  border: 1px dashed #ddd;
}
.selected-image {
  height: 250px !important;
  border-radius: 0.25rem !important;
  border: 1px solid #ddd !important;
}
</style>
