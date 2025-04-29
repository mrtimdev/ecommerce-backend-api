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
        <form @submit.prevent="handleSubmit" enctype="multipart/form-data">
          <div class="p-4 md:p-5">
            <div>
              <Label for_id="car"> {{ $t("select") }} {{ $t("car") }}</Label>
              <MultiSelect
                id="car"
                v-model="form.car"
                :options="cars"
                :multiple="false"
                track-by="id"
                :custom-label="(option) => `[${option.code}] - ${option.name}`"
              >
              </MultiSelect>
              <InputError :message="form.errors.car" class="mt-2" />
            </div>
          </div>
          <div
            class="modal-footer p-4 md:p-5 border-t border-gray-300 dark:border-gray-400"
          >
            <div class="flex justify-center gap-5 items-center">
              <button
                @click="closeModal"
                type="button"
                class="w-full focus:outline-none text-white bg-rose-700 hover:bg-rose-800 focus:ring-2 focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5 dark:bg-rose-600 dark:hover:bg-rose-700 dark:focus:ring-rose-900"
              >
                {{ $t("close") }}
              </button>
              <button
                class="w-full focus:outline-none text-white bg-purple-700 hover:bg-purple-800 focus:ring-2 focus:ring-purple-300 font-medium rounded-lg text-sm px-5 py-2.5 dark:bg-purple-600 dark:hover:bg-purple-700 dark:focus:ring-purple-900"
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
import InputError from "@/Components/InputError.vue";
import { getCurrentInstance, ref, nextTick } from "vue";
import { useForm, router } from "@inertiajs/vue3";
import { useI18n } from "vue-i18n";
const { t } = useI18n();
import { events } from "@/events";

import { useMainStore } from "@/stores/main";

const { proxy } = getCurrentInstance();

const mainStore = useMainStore();

const isVisible = ref(false);
const modal_title = ref("featured.add");
const event_type = ref("add");
const code = ref(null);

const props = defineProps({
  showModal: {
    type: Boolean,
    default: false,
  },
  cars: Array,
});
const form = useForm({
  car: null,
});
const closeModal = () => {
  isVisible.value = false;
  events.emit("modal:close");
};
events.on("modal:carFeatured:open", (data) => {
  modal_title.value = data.modal_title || "featured.add";
  event_type.value = data.event_type;
  isVisible.value = true;
  form.errors = {};
  if (event_type.value === "edit" && data.item) {
    nextTick(() => {
      form.id = data.item.id;
      form.code = data.item.code;
      form.name = data.item.name;
      form.car = data.item.car;
    });
  } else {
    form.id = null;
    form.code = "";
    form.name = "";
    form.car = null;
  }

  nextTick(() => {
    Array.from(document.querySelectorAll(".multiselect__tags")).forEach((element) => {
      element.classList.add(...mainStore.inputClasses);
    });
    Array.from(document.querySelectorAll(".multiselect__input")).forEach((element) => {
      element.classList.add(...mainStore.inputClasses);
    });
  });
});

const handleSubmit = () => {
  form.post(route("cars.featured.store"), {
    onSuccess: () => {
      const Toast = proxy.$swal.mixin({
        toast: true,
        position: "top-end",
        showConfirmButton: false,
        timer: 3000,
        timerProgressBar: true,
        didOpen: (toast) => {
          toast.onmouseenter = proxy.$swal.stopTimer;
          toast.onmouseleave = proxy.$swal.resumeTimer;
        },
      });
      Toast.fire({
        icon: "success",
        html: `<b>${form.car.code}:</b> added to featured successfully.`,
      });
      events.emit("modal:success");
      isVisible.value = false;
    },
    onError: () => {
      isVisible.value = true;
    },
  });
};

events.on("delete-items", (ids) => {
  var routeName = route("options.destroy.selected", {
    ids: ids,
  });
  form.post(routeName, {
    preserveScroll: true,
    onSuccess: () => {
      events.emit("confirm:cancel");
      events.emit("confirm:success");
      events.emit("toaster", {
        type: "success",
        action: "delete",
        message: `${t("item.count", ids.length)} ${t("successfully_deleted")}`,
      });
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
