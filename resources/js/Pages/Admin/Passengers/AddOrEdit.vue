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
        <form @submit.prevent="submitForm">
          <div class="p-4 md:p-5">
            <div class="grid gap-6 mb-6 grid-cols-2">
              <div>
                <label
                  for="code"
                  class="block mb-2 text-sm font-medium text-gray-700 dark:text-white"
                  >{{ $t("code") }}</label
                >
                <input
                  type="text"
                  ref="code"
                  id="code"
                  v-model="form.code"
                  class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-purple-500 focus:border-purple-500 block w-full p-2.5 dark:bg-boxdark-1 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-purple-500 dark:focus:border-purple-500 dark:bg-tim-11"
                  :placeholder="$t('code')"
                />
                <InputError :message="form.errors.code" class="mt-2" />
              </div>
              <div>
                <label
                  for="no"
                  class="block mb-2 text-sm font-medium text-gray-700 dark:text-white"
                  >{{ $t("no") }}</label
                >
                <input
                  type="text"
                  ref="no"
                  id="no"
                  v-model="form.no"
                  class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-purple-500 focus:border-purple-500 block w-full p-2.5 dark:bg-boxdark-1 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-purple-500 dark:focus:border-purple-500 dark:bg-tim-11"
                  :placeholder="$t('no')"
                />
                <InputError :message="form.errors.no" class="mt-2" />
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
import { ref, nextTick } from "vue";
import { useI18n } from "vue-i18n";
const { t } = useI18n();
import { events } from "@/events";
import { useForm } from "@inertiajs/vue3";

const props = defineProps({
  showModal: {
    type: Boolean,
    default: false,
  },
});

const form = useForm({
  id: null,
  no: "",
  code: "",
});

const isVisible = ref(false);
const code = ref(null);
const modal_title = ref("passenger.add");
const event_type = ref("add");
events.on("modal:open", (data) => {
  modal_title.value = data.modal_title || "passenger.add";
  isVisible.value = true;
  modal_title.value = data.modal_title;
  event_type.value = data.event_type;

  form.reset("id", "no", "code");

  if (event_type.value === "edit") {
    form.id = data.item.id;
    form.no = data.item.no;
    form.code = data.item.code;
  }
  nextTick(() => {
    code.value.focus();
  });
});

const submitForm = () => {
  const routeName =
    event_type.value === "edit" ? "passengers.update" : "passengers.store";
  const routeParams = event_type.value === "edit" ? form.id : null;

  form.post(route(routeName, routeParams), {
    preserveScroll: true,
    onSuccess: () => {
      events.emit("modal:success");
      form.clearErrors();
    },
    onError: () => {},
  });
};

const closeModal = () => {
  events.emit("modal:close");
  isVisible.value = false;
  form.clearErrors();
};
events.on("modal:success", () => {
  isVisible.value = true;
  form.reset("id", "no", "code");
  if (event_type.value === "edit") {
    isVisible.value = false;
  }
  form.clearErrors();
});

events.on("delete-items", (ids) => {
  var routeName = route("passengers.destroy.selected", {
    ids: ids,
  });
  form.post(routeName, {
    preserveScroll: true,
    onSuccess: () => {
      events.emit("confirm:cancel");
      events.emit("confirm:success");
      events.emit("toaster", {
        type: "success",
        message: `${t("item.count", ids.length)} ${t("successfully_deleted")}`,
      });
    },
  });
});
</script>
