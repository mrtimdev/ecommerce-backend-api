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
        <form @submit.prevent="changeGalleryImages" enctype="multipart/form-data">
          <div class="p-4 md:p-5">
            <div>
              <div
                class="grid gap-6 grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 mb-6"
              >
                <div
                  v-for="(image, index) in carStore.galleries"
                  :key="index"
                  class="relative"
                >
                  <div
                    @click="removeGallery(image.id)"
                    class="cursor-pointer absolute right-0 top-[-15px] z-index-2 text-gray-400 bg-transparent bg-rose-200 hover:bg-rose-300 hover:text-gray-900 rounded-full text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white"
                    data-modal-hide="static-modal"
                  >
                    <svg
                      class="w-3 h-3"
                      aria-hidden="true"
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
                  </div>
                  <img
                    :src="image.image_full_path"
                    alt="Gallery Image"
                    class="w-32 h-32 object-cover rounded-md"
                  />
                </div>
              </div>
            </div>
            <div class="grid gap-6 mb-6 md:grid-cols-1">
              <div>
                <Label
                  for_id="gallery_images"
                  class="block mb-2 text-sm font-medium text-gray-900 dark:text-white"
                  >{{ $t("gallery_images") }}</Label
                >
                <FileUpload
                  :multiple="true"
                  target_input="gallery_images"
                  v-model="form.gallery_images"
                />
                <InputError :message="form.errors.gallery_images" class="mt-2" />
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
import { ref, watch, nextTick } from "vue";
import { useI18n } from "vue-i18n";
const { t } = useI18n();
import { useCar } from "@/stores/car";
import { events } from "@/events";
import { useForm, router } from "@inertiajs/vue3";

const carStore = useCar();

const props = defineProps({
  showModal: {
    type: Boolean,
    default: false,
  },
});

const form = useForm({
  gallery_images: null,
});

const isVisible = ref(false);
const car_id = ref(null);
const modal_title = ref("change_gallery_images");
events.on("modal:changgallery:open", (data) => {
  modal_title.value = data.modal_title || "change_gallery_images";
  isVisible.value = true;
  if (data.item) {
    car_id.value = data.item.id;
    carStore.getCarGalleries(car_id.value);
  }
});

const closeModal = () => {
  events.emit("modal:close");
  isVisible.value = false;
  carStore.clearItem();
  car_id.value = null;
};
events.on("remove-gallery:success", () => {});

const changeGalleryImages = (e) => {
  axios
    .post(route("cars.updateGallery", { car: car_id.value }), form, {
      headers: {
        "Content-Type": "multipart/form-data",
      },
    })
    .then((res) => {
      carStore.getCarGalleries(car_id.value);
      events.emit("modal:success");
      form.gallery_images = null;
      form.errors = {};
      events.emit("toaster", {
        type: "success",
        action: "update",
        message: `${t("galleries")} ${t("successfully_updated")}`,
      });
    })
    .catch((error) => {
      form.errors.gallery_images = error.response?.data.message;
    });
};

const removeGallery = (id) => {
  axios.post(route("cars.removeGallery", { carImage: id })).then((res) => {
    carStore.getCarGalleries(car_id.value);
    events.emit("modal:success");
  });
};
</script>
