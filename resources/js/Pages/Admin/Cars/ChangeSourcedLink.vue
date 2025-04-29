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
        <form @submit.prevent="changeSourcedLink" enctype="multipart/form-data">
          <div class="p-4 md:p-5">
            <div class="grid gap-6 mb-6 md:grid-cols-1">
              <div>
                <Label
                  for_id="sourced_link"
                  class="block mb-2 text-sm font-medium text-gray-700 dark:text-white"
                  >{{ $t("sourced_link") }}</Label
                >
                <input
                  type="text"
                  ref="sourced_link"
                  id="sourced_link"
                  v-model="form.sourced_link"
                  @change="handleSourcedLinkChange"
                  class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-purple-500 focus:border-purple-500 block w-full p-2.5 dark:bg-boxdark-1 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-purple-500 dark:focus:border-purple-500 dark:bg-tim-11"
                  :placeholder="$t('sourced_link')"
                />
                <InputError :message="form.errors.sourced_link" class="mt-2" />
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
import Label from "@/Components/Others/Label.vue";
import { ref, nextTick } from "vue";
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
  sourced_link: "",
});
const sl_form = useForm({
  sourced_link: "",
});

const isVisible = ref(false);
const modal_title = ref("change_sourced_link");
const sourced_link = ref(null);
events.on("modal:sourcedLink:open", (data) => {
  modal_title.value = "sourced_link";
  isVisible.value = true;
  if (data.item) {
    carStore.getCarById(data.item.id);
    if (data.item.sourced_link) {
      form.sourced_link = data.item.sourced_link;
    }
  }
  nextTick(() => {
    sourced_link.value.focus();
  });
});

const closeModal = () => {
  events.emit("modal:close");
  isVisible.value = false;
  carStore.clearItem();
};
events.on("remove-gallery:success", () => {});

const handleSourcedLinkChange = (e) => {
  sl_form.sourced_link = form.sourced_link;
  sl_form.post(route("cars.handle-source-link-edit", carStore.car.id), {
    preserveScroll: true,
    onSuccess: (res) => {
      form.clearErrors("sourced_link");
    },
    onError: () => {
      form.setError("sourced_link", sl_form.errors.sourced_link);
    },
  });
};

const changeSourcedLink = (e) => {
  NProgress.start();
  axios
    .post(route("cars.updateSourcedLink", { car: carStore.car.id }), form, {
      headers: {
        "Content-Type": "multipart/form-data",
      },
    })
    .then((res) => {
      carStore.getCarById(carStore.car.id);
      events.emit("modal:success");
      form.errors = {};
      events.emit("toaster", {
        type: "success",
        action: "update",
        message: `[${carStore.car.code}] ${t("sourced_link")} ${t(
          "successfully_updated"
        )}`,
      });
      NProgress.done();
      NProgress.remove();
      nextTick(() => {
        sourced_link.value.focus();
        isVisible.value = false;
      });
    })
    .catch((error) => {
      form.errors.sourced_link = error.response?.data.message;
      NProgress.done();
      NProgress.remove();
      nextTick(() => {
        sourced_link.value.focus();
      });
    });
};
</script>
