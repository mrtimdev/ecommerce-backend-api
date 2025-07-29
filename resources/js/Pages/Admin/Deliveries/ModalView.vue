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
        <div v-if="user">{{ user.name }}</div>
      </template>
      <template #body>
        <div class="p-4 md:p-5">
          <div class="max-w-2xl mx-auto shadow-lg rounded-lg overflow-hidden">
            <!-- Cover Image -->
            <div class="relative">
              <img
                :src="user.cover_full_path"
                alt="Cover"
                class="w-full h-40 object-cover"
              />
              <!-- Avatar Image -->
              <div class="absolute -bottom-16 left-4">
                <img
                  :src="user.avatar_full_path"
                  alt="Avatar"
                  class="w-32 h-32 rounded-full border-4 border-white object-cover"
                />
              </div>
            </div>

            <!-- User Info -->
            <div class="mt-16 p-6">
              <div class="text-center">
                <h2 class="text-2xl font-semibold">{{ user.name }}</h2>
                <p class="text-gray-500">{{ user.username }}</p>
              </div>

              <div class="mt-4 space-y-2">
                <div class="grid grid-cols-2 gap-4">
                  <div><span class="font-semibold">Email:</span> {{ user.email }}</div>
                  <div><span class="font-semibold">Phone:</span> {{ user.phone }}</div>
                  <div><span class="font-semibold">Gender:</span> {{ user.gender }}</div>
                  <div>
                    <span class="font-semibold">Date of Birth:</span> {{ user.dob }}
                  </div>
                </div>

                <div class="grid grid-cols-2 gap-4 mt-4">
                  <div>
                    <span class="font-semibold">Company:</span> {{ user.company }}
                  </div>
                  <div>
                    <span class="font-semibold">Address:</span> {{ user.address }}
                  </div>
                  <div>
                    <span class="font-semibold">Status:</span>
                    <span v-html="statusFormat_(user.is_active)"></span>
                  </div>
                </div>
              </div>

              <div class="mt-6 space-y-2">
                <div>
                  <span class="font-semibold">Created At:</span>
                  {{ formatDate(user.created_at) }}
                </div>
                <div>
                  <span class="font-semibold">Updated At:</span>
                  {{ formatDate(user.updated_at) }}
                </div>
                <div>
                  <span class="font-semibold">Email Verified At:</span>
                  {{ formatDate(user.email_verified_at) }}
                </div>
              </div>
            </div>
          </div>
        </div>
      </template>
    </modal>
  </Teleport>
</template>

<script setup>
import { ref } from "vue";
import { useI18n } from "vue-i18n";
const { t } = useI18n();
import { events } from "@/events";
import useHelper from "@/composables/useHelper";
const { statusFormat, formatDate } = useHelper();

const props = defineProps({
  showModal: {
    type: Boolean,
    default: false,
  },
});

const user = ref(false);
const isVisible = ref(false);
events.on("modal:modalview:open", (data) => {
  isVisible.value = true;
  user.value = data.item;
});

const closeModal = () => {
  events.emit("modal:close");
  isVisible.value = false;
};

const statusFormat_ = (status) => {
  return statusFormat(status ? "active" : "inactive");
};
</script>
