<script setup>
import HeaderArea from "@/Components/Header/HeaderArea.vue";
import SidebarArea from "@/Components/Sidebar/SidebarArea.vue";
import ConfirmPopup from "@/Components/Others/Popups/ConfirmPopup.vue";
import ToasterWrapper from "@/Components/Others/Toaster/ToasterNotifications.vue";
</script>

<template>
  <!-- ===== Page Wrapper Start ===== -->
  <div class="flex h-screen overflow-hidden">
    <!-- popup component -->
    <ToasterWrapper />
    <ConfirmPopup />
    <!-- ===== Sidebar Start ===== -->
    <SidebarArea />
    <!-- ===== Sidebar End ===== -->

    <!-- ===== Content Area Start ===== -->
    <div class="relative flex flex-1 flex-col overflow-y-auto overflow-x-hidden">
      <!-- ===== Header Start ===== -->
      <HeaderArea />
      <!-- ===== Header End ===== -->

      <!-- ===== Main Content Start ===== -->
      <main>
        <div
          class="mx-auto max-w-screen-2xl p-4 md:p-6 2xl:p-10 bg-gray-200 dark:bg-[#1a2035]"
        >
          <div class="content dark:bg-boxdark-1 bg-white">
            <slot></slot>
          </div>
        </div>
      </main>
      <!-- ===== Main Content End ===== -->
    </div>
  </div>
  <!-- ===== Page Wrapper End ===== -->
</template>
<style src="vue-multiselect/dist/vue-multiselect.css"></style>
<style lang="scss">
.dark {
  .multiselect__single {
    background: #202940 !important;
  }
  .note-frame {
    color: #ffffff !important;
  }
}
.table tbody tr:nth-of-type(odd) {
  background-color: rgba(0, 0, 0, 0.05);
}

.dark {
  .table tr th,
  .table tr td {
    border-color: rgba(209, 199, 199, 0.05) !important;
    border: 1px solid;
  }
}
.table {
  tr {
    td,
    th {
      border-color: #dedada !important;
      border: 1px solid;
    }
  }
}

.no-bg-tr tbody tr:nth-of-type(odd) {
  background-color: transparent !important;
}
.checkbox-col,
.action-col {
  text-align: center !important;
}

.dt-length {
  > select {
    width: 50px !important;
  }
}
.table {
  .text-center {
    text-align: center !important;
  }
}
</style>
