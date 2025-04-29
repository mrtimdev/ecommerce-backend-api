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
        <form @submit.prevent="videoFormSubmit" enctype="multipart/form-data">
          <div class="grid gap-6 md:grid-cols-2 p-4 md:p-5">
            <div class="mb-6">
              <Label :isRequired="true" for_id="code"> {{ $t("code") }}</Label>
              <input
                type="text"
                id="code"
                ref="code"
                v-model="form.code"
                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-purple-500 focus:border-purple-500 block w-full p-2.5 dark:bg-boxdark-1 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-purple-500 dark:focus:border-purple-500"
                :placeholder="$t('code')"
              />
              <InputError :message="form.errors.code" class="mt-2" />
            </div>

            <div class="mb-6">
              <CountryList id="country" v-model="form.country" />
              <InputError :message="form.errors.name" class="mt-2" />
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
import CountryList from "@/Components/Others/CountryList.vue";
import Label from "@/Components/Others/Label.vue";
import InputError from "@/Components/InputError.vue";
import { ref, nextTick } from "vue";
import { useForm } from "@inertiajs/vue3";
import { useI18n } from "vue-i18n";
const { t } = useI18n();
import { events } from "@/events";

const isVisible = ref(false);
const modal_title = ref("country.add");
const event_type = ref("add");
const item = ref(false);

events.on("modal:success", () => {
  isVisible.value = false;
});

const form = useForm({
  id: null,
  name: "",
  code: "",
  flag: "",
  dial_code: "",
  country: null,
  is_active: true,
});

const closeModal = () => {
  isVisible.value = false;
  form.is_active = false;
  events.emit("modal:close");
};
events.on("modal:open", (data) => {
  modal_title.value = data.modal_title;
  event_type.value = data.event_type;
  isVisible.value = true;

  // form.clearErrors()
  if (event_type.value === "edit" && data.item) {
    form.id = data.item.id;

    item.value = data.item;
    form.country = {
      name: data.item.name,
      code: data.item.flag_code,
      flag: data.item.flag,
      dial_code: data.item.dial_code,
    };
    form.code = data.item.code;
  } else {
    form.country = null;
    form.code = "";
    item.value = false;
  }
  console.log({ data }, form.country);
});

const props = defineProps({
  showModal: {
    type: Boolean,
    default: false,
  },
});

const videoFormSubmit = () => {
  const cForm = useForm({
    name: form.country.name,
    flag_code: form.country.code,
    flag: form.country.flag,
    dial_code: form.country.dial_code,
    code: form.code,
    is_active: form.is_active,
  });

  cForm.id = item.value.id;
  if (event_type.value === "edit") {
    cForm.post(route("settings.countries.update", cForm), {
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
    cForm.post(route("settings.countries.store"), {
      onSuccess: () => {
        nextTick(() => {
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
  var routeName = route("settings.countries.destroy.selected", {
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
