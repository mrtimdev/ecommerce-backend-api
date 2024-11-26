<template>
  <Teleport to="body">
    <modal
      size="lg"
      :show="isVisible"
      :show-footer="false"
      :show-confirm-button="true"
      button-confirm-label="save"
      @close="closeModal"
    >
      <template #title> {{ t(modal_title) }} - {{ carStore.car.code }} </template>
      <template #body>
        <div class="detail-content">
          <div v-if="carStore.loading" class="flex items-center justify-center p-6">
            <LoadingIcon :size="100" />
          </div>
          <div v-if="!carStore.loading && carStore.car" class="car-details p-6 pt-0 mt-4">
            <div
              class="flex space-x-2"
              v-if="carStore.car.hot_marks && !_.isEmpty(carStore.car.hot_marks)"
            >
              <span
                v-for="(mark, index) in carStore.car.hot_marks"
                :key="index"
                :class="randomBackground(index)"
                class="text-white text-xs py-1 px-3 rounded-md"
              >
                {{ mark }}
              </span>
            </div>

            <div
              class="mt-6"
              v-if="carStore.car.galleries && !_.isEmpty(carStore.car.galleries)"
            >
              <h3 class="text-2xl font-semibold mb-2">{{ $t("galleries") }}</h3>

              <swiper
                :spaceBetween="30"
                :centeredSlides="true"
                :autoplay="{
                  delay: 2500,
                  disableOnInteraction: false,
                }"
                :pagination="{
                  clickable: true,
                }"
                :navigation="true"
                :modules="modules"
                class="mySwiper"
              >
                <swiper-slide>
                  <img
                    :src="carStore.car.featured_image_full_path"
                    class="w-full !h-64 object-cover"
                    alt="Car Featured Image"
                  />
                </swiper-slide>
                <swiper-slide
                  v-for="(image, index) in carStore.car.galleries"
                  :key="index"
                >
                  <img
                    :src="image"
                    class="w-full !h-64 object-cover"
                    :alt="'Slide ' + (index + 1)"
                  />
                </swiper-slide>
              </swiper>
            </div>

            <!-- <img
              :src="carStore.car.featured_image_full_path"
              alt="Car Featured Image"
              class="w-full h-64 object-cover rounded-md mb-4"
            /> -->

            <h2 class="text-3xl font-bold mb-2">{{ carStore.car.name }}</h2>

            <div class="grid grid-cols-2 gap-4">
              <div>
                <p>
                  <strong>{{ $t("price") }}: </strong>
                  <span class="!text-purple-500">
                    {{ formatMoney(carStore.car.price) }}</span
                  >
                </p>
              </div>
            </div>
            <div class="flex justify-between mb-4">
              <p>
                <strong>{{ $t("status") }}: </strong>
                <span v-html="statusFormat(carStore.car.status)"></span>
              </p>
            </div>
            <div class="flex justify-between mb-4">
              <p>
                <strong>{{ $t("listing_date") }}: </strong>
                <span v-html="formatDate(carStore.car.listing_date)"></span>
              </p>
            </div>
            <div class="flex justify-between mb-4">
              <p>
                <strong>{{ $t("sourced_link") }}: </strong>
                <a
                  :href="carStore.car.sourced_link"
                  class="hover:underline text-blue-700"
                  target="_blank"
                  v-if="carStore.car.sourced_link"
                >
                  <i class="fi fi-rr-link text-[12px]"></i>
                  {{ carStore.car.sourced_link }}
                </a>
                <span v-else>N/A</span>
              </p>
            </div>
            <fieldset
              class="border border-gray-300 dark:border-gray-400 p-4 rounded-lg bg-white dark:bg-boxdark"
            >
              <legend
                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white"
              >
                {{ $t("payment_details") }}
              </legend>
              <div>
                <table class="table no-bg-tr w-full text-sm text-left rtl:text-right">
                  <tbody
                    class="text-gray-700 dark:text-gray-100 bg-white dark:bg-boxdark"
                  >
                    <tr
                      v-for="(payment, index) in carStore.car.payment_details"
                      :key="index"
                    >
                      <td
                        class="py-2 px-4 font-sm"
                        :class="{ 'font-bold': payment.title === 'Total Price' }"
                      >
                        {{ payment.title }}:
                      </td>
                      <td
                        class="py-2 px-4 !text-right"
                        :class="{
                          '!text-purple-600 font-bold': payment.title === 'Total Price',
                        }"
                      >
                        {{ formatMoney(payment.value) }}
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </fieldset>

            <fieldset
              class="border border-gray-300 dark:border-gray-400 p-4 mt-4 rounded-lg bg-white dark:bg-boxdark"
            >
              <legend
                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white"
              >
                {{ $t("payment_terms") }}
              </legend>
              <div>
                <table class="table no-bg-tr w-full text-sm text-left rtl:text-right">
                  <tbody
                    class="text-gray-700 dark:text-gray-100 bg-white dark:bg-boxdark"
                  >
                    <tr
                      v-for="(payment, index) in carStore.car.payment_terms"
                      :key="index"
                    >
                      <td class="py-2 px-4 font-sm">{{ payment.title }}:</td>
                      <td class="py-2 px-4 !text-right">
                        {{ formatMoney(payment.value) }}
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </fieldset>

            <div class="pb-4 mb-4">
              <h3 class="text-lg font-medium text-gray-600 dark:text-gray-200 mb-2">
                {{ $t("basic_information") }}
              </h3>
              <table class="table no-bg-tr w-full text-sm text-left rtl:text-right">
                <tbody class="text-gray-700 dark:text-gray-100 bg-white dark:bg-boxdark">
                  <tr>
                    <td class="py-2 px-4 font-sm">{{ $t("item_code") }}:</td>
                    <td class="py-2 px-4">
                      {{ carStore.car.code }}
                    </td>
                  </tr>
                  <tr>
                    <td class="py-2 px-4 font-sm">{{ $t("brand") }}:</td>
                    <td class="py-2 px-4">
                      {{ carStore.car.brand }}
                    </td>
                  </tr>
                  <tr>
                    <td class="py-2 px-4 font-sm">{{ $t("model") }}:</td>
                    <td class="py-2 px-4">
                      {{ carStore.car.model }}
                    </td>
                  </tr>
                  <tr>
                    <td class="py-2 px-4 font-sm">{{ $t("category") }}:</td>
                    <td class="py-2 px-4">
                      {{ carStore.car.category }}
                    </td>
                  </tr>

                  <tr>
                    <td class="py-2 px-4 font-sm">{{ $t("year") }}:</td>
                    <td class="py-2 px-4">
                      {{ carStore.car.year }}
                    </td>
                  </tr>
                  <tr>
                    <td class="py-2 px-4 font-sm">{{ $t("mileage") }}:</td>
                    <td class="py-2 px-4">
                      {{ carStore.car.mileage }}
                    </td>
                  </tr>

                  <tr>
                    <td class="py-2 px-4 font-sm">{{ $t("condition") }}:</td>
                    <td class="py-2 px-4">
                      {{ carStore.car.condition }}
                    </td>
                  </tr>
                  <tr>
                    <td class="py-2 px-4 font-sm">{{ $t("fuel_type") }}:</td>
                    <td class="py-2 px-4">
                      {{ carStore.car.fuel_type }}
                    </td>
                  </tr>
                  <tr>
                    <td class="py-2 px-4 font-sm">{{ $t("drive_type") }}:</td>
                    <td class="py-2 px-4">
                      {{ carStore.car.drive_type }}
                    </td>
                  </tr>
                  <tr>
                    <td class="py-2 px-4 font-sm">{{ $t("transmission_type") }}:</td>
                    <td class="py-2 px-4">
                      {{ carStore.car.transmission_type }}
                    </td>
                  </tr>
                  <tr>
                    <td class="py-2 px-4 font-sm">{{ $t("steering") }}:</td>
                    <td class="py-2 px-4">
                      {{ carStore.car.steering }}
                    </td>
                  </tr>
                  <tr>
                    <td class="py-2 px-4 font-sm">{{ $t("engine_volume") }}:</td>
                    <td class="py-2 px-4">
                      {{ carStore.car.engine_volume }}
                    </td>
                  </tr>
                  <tr>
                    <td class="py-2 px-4 font-sm">{{ $t("door") }}:</td>
                    <td class="py-2 px-4">
                      {{ carStore.car.door }}
                    </td>
                  </tr>
                  <tr>
                    <td class="py-2 px-4 font-sm">{{ $t("cylinder") }}:</td>
                    <td class="py-2 px-4">
                      {{ carStore.car.cylinder }}
                    </td>
                  </tr>
                  <tr>
                    <td class="py-2 px-4 font-sm">{{ $t("number_of_passenger") }}:</td>
                    <td class="py-2 px-4">
                      {{ $t("seat.count", carStore.car.number_of_passenger) }}
                    </td>
                  </tr>
                  <tr>
                    <td class="py-2 px-4 font-sm">{{ $t("vehicle_type") }}:</td>
                    <td class="py-2 px-4">
                      {{ carStore.car.size }}
                    </td>
                  </tr>
                  <tr>
                    <td class="py-2 px-4 font-sm">{{ $t("color") }}:</td>
                    <td class="py-2 px-4">
                      <div class="flex gap-1">
                        <span
                          :style="{ background: carStore.car.color.code }"
                          class="shadow-2 w-[20px] h-[20px] rounded-full"
                        ></span>
                        <span>{{ carStore.car.color.name }}</span>
                      </div>
                    </td>
                  </tr>
                  <tr>
                    <td class="py-2 px-4 font-sm">{{ $t("location") }}:</td>
                    <td class="py-2 px-4">
                      <div v-html="countryFlagFormat(carStore.car.location, true)"></div>
                    </td>
                  </tr>

                  <tr>
                    <td class="py-2 px-4 font-sm">{{ $t("descriptions") }}:</td>
                    <td class="py-2 px-4">
                      <p
                        class="text-sm text-gray-400 mb-4"
                        :style="{ whiteSpace: 'pre-line' }"
                      >
                        {{ carStore.car.description }}
                      </p>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>

            <!-- Additional Information -->
            <fieldset class="border border-gray-300 dark:border-gray-400 p-4 rounded-lg">
              <legend
                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white"
              >
                {{ $t("featured_information") }}
              </legend>
              <div
                class="grid gap-4 grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4"
              >
                <div
                  v-for="(feature_info, index) in carStore.car.featured_information"
                  :key="index"
                >
                  <p>
                    <strong class="text-[12px]">{{ feature_info.title }}: </strong>
                    <span
                      class="text-xs"
                      v-html="statusFormat(feature_info.value)"
                    ></span>
                  </p>
                </div>
              </div>
            </fieldset>
            <div>
              <h3 class="text-lg font-medium text-gray-600 dark:text-gray-200 mt-2">
                {{ $t("options") }}
              </h3>
              <hr class="my-1 border-gray-700" />
              <div class="grid gap-2 grid-cols-1">
                <div v-for="(option, index) in carStore.car.options" :key="index">
                  <p class="!text-purple-600">{{ option.title }}</p>
                  <div
                    v-for="(item, i) in option.data"
                    :key="i"
                    class="grid gap-2 grid-cols-1 ml-[20px]"
                  >
                    <span class="text-md">. {{ item }}</span>
                  </div>
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
import _ from "lodash";
import { ref } from "vue";
import { useI18n } from "vue-i18n";
const { t } = useI18n();
import { useCar } from "@/stores/car";
import { events } from "@/events";
import useHelper from "@/composables/useHelper";

import { Navigation, Pagination, Scrollbar, A11y, Autoplay } from "swiper/modules";

// Import Swiper Vue.js components
import { Swiper, SwiperSlide } from "swiper/vue";

// Import Swiper styles
import "swiper/css";
import "swiper/css/navigation";
import "swiper/css/pagination";
import "swiper/css/scrollbar";
import "swiper/css/autoplay";

const modules = [Navigation, Pagination, Scrollbar, A11y, Autoplay];

const { statusFormat, formatMoney, countryFlagFormat, formatDate } = useHelper();

const carStore = useCar();

// const formatDate = (dateStr) => {
//   const options = { year: "numeric", month: "long", day: "numeric" };
//   return new Date(dateStr).toLocaleDateString(undefined, options);
// };

const props = defineProps({
  showModal: {
    type: Boolean,
    default: false,
  },
});

const isVisible = ref(false);
const car = ref(false);

const modal_title = ref("car.details");
events.on("modal:modalview:open", async (data) => {
  modal_title.value = data.modal_title || "car.details";
  isVisible.value = true;
  if (data.item) {
    carStore.getCarById(data.item.id);
  }
});

const randomBackground = (index) => {
  const colors = [
    "!bg-purple-600",
    "!bg-rose-600",
    "!bg-blue-600",
    "!bg-green-600",
    "!bg-yellow-600",
    "!bg-red-600",
    "!bg-teal-600",
    "!bg-indigo-600",
    "!bg-pink-600",
    "!bg-lime-600",
    "!bg-emerald-600",
    "!bg-fuchsia-600",
    "!bg-cyan-600",
    "!bg-violet-600",
    "!bg-orange-600",
    "!bg-sky-600",
    "!bg-zinc-600",
    "!bg-stone-600",
    "!bg-slate-600",
    "!bg-neutral-600",
    "!bg-warm-gray-600",
  ];

  return colors[index % colors.length] || "!bg-gray-300";
};

const closeModal = () => {
  events.emit("modal:close");
  isVisible.value = false;
  carStore.clearItem();
};
events.on("modal:success", () => {
  isVisible.value = false;
});
</script>

<style>
.swiper {
  width: 100%;
  height: 100%;
}

.swiper-slide {
  text-align: center;
  font-size: 18px;
  background: #fff;

  /* Center slide text vertically */
  display: flex;
  justify-content: center;
  align-items: center;
}

.swiper-slide img {
  display: block;
  width: 100%;
  height: 100%;
  object-fit: cover;
}
</style>
