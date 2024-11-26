<template>
  <OrderIndex>
    <template #detail>
      <Head :title="page_title" />
      <Teleport to="body">
        <modal
          size="md"
          :show="isModalVisible"
          :show-footer="false"
          :show-confirm-button="true"
          button-confirm-label="save"
          @close="closeModal"
        >
          <template #title>
            <span class="!text-purple-600">{{ page_title }}</span>
          </template>
          <template #body>
            <form @submit.prevent="submitForm">
              <div class="p-4 md:p-5">
                <div class="grid gap-6 mb-6 md:grid-cols-2">
                  <div>
                    <label
                      for="name"
                      class="block mb-2 text-sm font-medium text-gray-700 dark:text-white"
                      >{{ $t("name") }}</label
                    >
                    <input
                      type="text"
                      ref="name"
                      id="name"
                      v-model="form.name"
                      class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-purple-500 focus:border-purple-500 block w-full p-2.5 dark:bg-boxdark-1 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-purple-500 dark:focus:border-purple-500 dark:bg-tim-11"
                      :placeholder="$t('name')"
                    />
                    <InputError :message="form.errors.name" class="mt-2" />
                  </div>
                  <div>
                    <label
                      for="display_name"
                      class="block mb-2 text-sm font-medium text-gray-700 dark:text-white"
                      >{{ $t("display_name") }}</label
                    >
                    <input
                      type="text"
                      ref="display_name"
                      id="display_name"
                      v-model="form.display_name"
                      class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-purple-500 focus:border-purple-500 block w-full p-2.5 dark:bg-boxdark-1 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-purple-500 dark:focus:border-purple-500 dark:bg-tim-11"
                      :placeholder="$t('display_name')"
                    />
                    <InputError :message="form.errors.display_name" class="mt-2" />
                  </div>
                </div>
                <div class="mb-6">
                  <Label
                    for_id="description"
                    class="block mb-2 text-sm font-medium text-gray-900 dark:text-white"
                    >{{ $t("description") }}</Label
                  >
                  <Textarea
                    rows="5"
                    id="description"
                    v-model="form.description"
                  ></Textarea>
                  <InputError :message="form.errors.description" class="mt-2" />
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
                    {{ button_name }}
                  </button>
                </div>
              </div>
            </form>
          </template>
        </modal>
      </Teleport>
    </template>
  </OrderIndex>
</template>

<script setup>
import Label from "@/Components/Others/Label.vue";
import Textarea from "@/Components/Others/Textarea.vue";
import { Head, router, useForm } from "@inertiajs/vue3";
import { onMounted, watch, ref, nextTick } from "vue";
import { events } from "@/events";
import OrderIndex from "./Index.vue";
import { useI18n } from "vue-i18n";
const { t } = useI18n();
const props = defineProps({
  show_modal: {
    type: Boolean,
    default: false,
  },
  role: {
    type: Object,
    default: false,
  },
});
const isModalVisible = ref(props.show_modal);
const page_title = ref(t("role.create"));
const button_name = ref(t("save"));
const name = ref(null);
const form = useForm({
  id: props.role ? (props.role.id ? props.role.id : null) : null,
  name: props.role ? (props.role.name ? props.role.name : "") : "",
  display_name: props.role
    ? props.role.display_name
      ? props.role.display_name
      : ""
    : "",
  description: props.role ? (props.role.description ? props.role.description : "") : "",
});

const submitForm = () => {
  if (props.role.id) {
    form.post(route("roles.update", form), {
      preserveScroll: true,
      onSuccess: () => {
        events.emit("toaster", {
          type: "success",
          action: "create",
          message: `${t("role")} [${form.name}] ${t("successfully_updated")}`,
        });
        form.reset();
      },
    });
  } else {
    form.post(route("roles.store"), {
      preserveScroll: true,
      onSuccess: () => {
        events.emit("toaster", {
          type: "success",
          action: "create",
          message: `${t("role")} [${form.name}] ${t("successfully_added")}`,
        });
        form.reset();
      },
    });
  }
};

watch(
  () => form.name,
  (newValue) => {
    form.name = newValue.toLowerCase();
  }
);

onMounted(() => {
  nextTick(() => {
    name.value.focus();
    if (props.role.id) {
      page_title.value = t("role.update");
      button_name.value = t("update");
    }
  });
});

const closeModal = () => {
  isModalVisible.value = false;
  router.get(route("roles.index"));
};
</script>
