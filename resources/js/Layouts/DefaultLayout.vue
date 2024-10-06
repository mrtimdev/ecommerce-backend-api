<script setup>
  import HeaderArea from '@/Components/Header/HeaderArea.vue'
  import SidebarArea from '@/Components/Sidebar/SidebarArea.vue'
  import ConfirmPopup from '@/Components/Others/Popups/ConfirmPopup.vue'
</script>

<template>
  <!-- ===== Page Wrapper Start ===== -->
  <div class="flex h-screen overflow-hidden">
    <!-- popup component -->
     <ConfirmPopup/>
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
        <div class="mx-auto max-w-screen-2xl p-4 md:p-6 2xl:p-10 bg-gray-200 dark:bg-[#1a2035]">
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
