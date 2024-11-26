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
        <form @submit.prevent="changeFeaturedImage" enctype="multipart/form-data">
          <div class="p-4 md:p-5">
            <div class="grid gap-6 mb-6 md:grid-cols-1">
              <div>
                <Label
                  for_id="featured_image"
                  class="block mb-2 text-sm font-medium text-gray-900 dark:text-white"
                  >{{ $t("featured_image") }}</Label
                >
                <FileUpload
                  v-model="form.featured_image"
                  target_input="featured_image"
                  :selectedFile="carStore.car.featured_image"
                />
                <InputError :message="form.errors.featured_image" class="mt-2" />
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
                class="w-full focus:outline-none text-white bg-rose-700 hover:bg-rose-800 focus:ring-1 focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5 dark:bg-rose-600 dark:hover:bg-rose-700 dark:focus:ring-rose-900"
              >
                {{ $t("close") }}
              </button>
              <button
                class="w-full focus:outline-none text-white bg-purple-700 hover:bg-purple-800 focus:ring-1 focus:ring-purple-300 font-medium rounded-lg text-sm px-5 py-2.5 dark:bg-purple-600 dark:hover:bg-purple-700 dark:focus:ring-purple-900"
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
import InputError from "@/Components/InputError.vue";
import FileUpload from "@/Components/Others/FileUpload.vue";
import Label from "@/Components/Others/Label.vue";
import { ref } from "vue";
import { useI18n } from "vue-i18n";
const { t } = useI18n();
import { useCar } from "@/stores/car";
import { events } from "@/events";
import { useForm } from "@inertiajs/vue3";

const carStore = useCar();

const props = defineProps({
  showModal: {
    type: Boolean,
    default: false,
  },
});

const form = useForm({
  featured_image: null,
});

const isVisible = ref(false);
const modal_title = ref("change_featured_image");
events.on("modal:featuredimage:open", (data) => {
  modal_title.value = data.modal_title || "change_featured_image";
  isVisible.value = true;
  if (data.item) {
    carStore.getCarById(data.item.id);
  }
});

const closeModal = () => {
  events.emit("modal:close");
  isVisible.value = false;
  carStore.clearItem();
};
events.on("remove-gallery:success", () => {});

const changeFeaturedImage = (e) => {
  axios
    .post(route("cars.updateFeaturedImage", { car: carStore.car.id }), form, {
      headers: {
        "Content-Type": "multipart/form-data",
      },
    })
    .then((res) => {
      carStore.getCarById(carStore.car.id);
      events.emit("modal:success");
      form.featured_image = null;
      form.errors = {};
      events.emit("toaster", {
        type: "success",
        action: "update",
        message: `${t("featured_image")} ${t("successfully_updated")}`,
      });
    })
    .catch((error) => {
      form.errors.featured_image = error.response?.data.message;
    });
};
</script>
