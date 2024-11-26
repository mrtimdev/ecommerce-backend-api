<template>
    <transition appear name="fade">
        <div
            class="relative w-full overflow-hidden rounded-md bg-opacity-80 p-4 shadow-lg backdrop-blur-2xl"
            :class="{
                'bg-rose-50 dark:bg-2x-dark-foreground': item.type === 'danger',
                'bg-green-50 dark:bg-2x-dark-foreground': item.type === 'success',
            }"
        >
            <!--Content-->
            <div class="flex items-center justify-between">
                <div class="flex items-start">
                    <i v-if="!item?.action && item.type === 'success'" class="mt-[3px] text-sm fi fi-sr-check  dark:text-green-600 text-green-600"></i>
			        <i v-if="!item?.action && item.type === 'danger'" class="mt-[3px] text-sm fi fi-rs-cross dark:text-rose-600 text-rose-600"></i>
			        <!-- action create update or delete -->
                    <i v-if="item?.action === 'delete'" class="mt-[3px] text-sm fi fi-rs-trash dark:text-purple-600 text-purple-600"></i>
			        <i v-if="item?.action === 'create'" class="mt-[3px] text-sm fi fi-rs-pen-clip dark:text-purple-600 text-purple-600"></i>
			        <i v-if="item?.action === 'update'" class="mt-[3px] text-sm fi fi-rs-refresh dark:text-purple-600 text-purple-600"></i>
                    <p
                        class="px-4 font-bold"
                        :class="{
                            'text-green-600': item.type === 'success',
                            'text-rose-600': item.type === 'danger',
                        }"
                    >
                        {{ item.message }}
                    </p>
                </div>
            </div>
        </div>
    </transition>
</template>

<script setup>
    defineProps({
        item: Object
    })
</script>
