<template>
  <div class="bg-gradient-to-br from-purple-100 to-teal-100">
    <div class="p-2" v-if="item && item.pdf_full_path">
      <div class="flex justify-start items-start gap-2">
        <Link
          :href="route('frontend.page.communities.index')"
          class="text-gray-700 pt-[13px]"
          ><i class="fi fi-sr-arrow-circle-left text-[30px]"></i
        ></Link>
        <span class="pt-[15px] text-[25px]">Community: {{ item.title }}</span>
      </div>

      <div class="flex justify-center items-center gap-[50px] pb-5">
        <div class="flex justify-center items-center gap-3">
          <button
            class="block focus:outline-none text-white bg-purple-700 hover:bg-purple-800 focus:ring-2 focus:ring-purple-300 font-medium rounded-lg text-sm px-5 py-2.5 dark:bg-purple-600 dark:hover:bg-purple-700 dark:focus:ring-purple-900"
            @click="page = page > 1 ? page - 1 : page"
          >
            Prev
          </button>
          <span>{{ page }} / {{ pages }}</span>
          <button
            class="block focus:outline-none text-white bg-purple-700 hover:bg-purple-800 focus:ring-2 focus:ring-purple-300 font-medium rounded-lg text-sm px-5 py-2.5 dark:bg-purple-600 dark:hover:bg-purple-700 dark:focus:ring-purple-900"
            @click="page = page < pages ? page + 1 : page"
          >
            Next
          </button>
        </div>
        <div class="flex justify-center items-center gap-3">
          <button
            class="block focus:outline-none text-white bg-purple-700 hover:bg-purple-800 focus:ring-2 focus:ring-purple-300 font-medium rounded-lg text-sm px-5 py-2.5 dark:bg-purple-600 dark:hover:bg-purple-700 dark:focus:ring-purple-900"
            @click="scale = scale > 0.25 ? scale - 0.25 : scale"
          >
            -
          </button>
          <span>{{ scale * 100 }}%</span>
          <button
            class="block focus:outline-none text-white bg-purple-700 hover:bg-purple-800 focus:ring-2 focus:ring-purple-300 font-medium rounded-lg text-sm px-5 py-2.5 dark:bg-purple-600 dark:hover:bg-purple-700 dark:focus:ring-purple-900"
            @click="scale = scale < 2 ? scale + 0.25 : scale"
          >
            +
          </button>
        </div>
        <div>
          <a
            :href="
              route('frontend.page.community.download.pdf', {
                communityItem: item.id,
              })
            "
            ><i class="fi fi-rr-download"></i
          ></a>
        </div>
      </div>

      <div
        class="overflow-x-auto sm:overflow-x-hidden flex justify-center items-center py-4"
      >
        <!-- <iframe :src="item.pdf_full_path" width="100%" height="500px"></iframe> -->
        <VuePDF
          :pdf="pdf"
          text-layer
          annotation-layer
          :page="page"
          :scale="scale"
          @loaded="onLoaded"
        />
      </div>
    </div>
  </div>
</template>

<script setup>
import { VuePDF, usePDF } from "@tato30/vue-pdf";
import "@tato30/vue-pdf/style.css";
import { Link } from "@inertiajs/vue3";
import { ref, onMounted } from "vue";
const prop = defineProps({
  item: Object,
  required: true,
});
const page = ref(1);
const scale = ref(1);

const { pdf, pages } = usePDF({
  url: prop.item.pdf_full_path,
  enableXfa: true,
});
onMounted(() => {
  NProgress.start();
});
const onLoaded = (value) => {
  NProgress.done();
  NProgress.remove();
};
</script>
