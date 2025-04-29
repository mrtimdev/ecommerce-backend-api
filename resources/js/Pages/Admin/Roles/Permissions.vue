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
                <div class="mb-2">
                  <span
                    >{{ $t("permissions_for") }}
                    <span class="!text-purple-500">: {{ role.name }}</span></span
                  >
                </div>
                <div class="grid gap-6 mb-6 md:grid-cols-2">
                  <div v-for="(permission, index) in permissions" :key="index">
                    <label
                      :for="'permissions_' + index"
                      class="block mb-2 text-sm font-medium text-gray-700 dark:text-white"
                      >{{ permission.name }}</label
                    >
                    <input
                      type="checkbox"
                      :id="'permissions_' + index"
                      :value="permission.id"
                      :checked="permission.assigned"
                      @change="handleChange"
                      class="w-4 h-4 text-purple-600 bg-gray-100 border-gray-300 rounded focus:ring-purple-500 dark:focus:ring-purple-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-boxdark-1 dark:border-gray-600"
                      :placeholder="$t('permissions')"
                    />
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
                    {{ $t("update") }}
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
import CheckBox from "@/Components/Others/CheckBox.vue";
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
  permissions: {
    type: Array,
    default: [],
  },
});
const isModalVisible = ref(props.show_modal);
const page_title = ref(t("change_permissions"));
const form = useForm({
  role_id: props.role ? (props.role.id ? props.role.id : null) : null,
  permissions: [],
});

const submitForm = () => {
  form.post(route("roles.change-permissions"), {
    preserveScroll: true,
    onSuccess: () => {
      events.emit("toaster", {
        type: "success",
        action: "create",
        message: `${t("permissions")} ${t("changed_success")}`,
      });
      form.reset();
    },
  });
};

onMounted(() => {
  for (const p of props.permissions) {
    if (p.assigned) {
      form.permissions.push(parseInt(p.id));
    }

    console.log(p);
  }
});

const handleChange = (e) => {
  const p = form.permissions;
  const value = parseInt(e.target.value);
  const is_checked = e.target.checked;
  if (is_checked) {
    if (!form.permissions.includes(value)) {
      form.permissions.push(value);
    }
  } else {
    form.permissions = form.permissions.filter(
      (val) => parseInt(val) !== parseInt(value)
    );
  }
};

const closeModal = () => {
  isModalVisible.value = false;
  router.get(route("roles.index"));
};
</script>
