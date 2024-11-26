<template>
    <div
		v-if="toasters.length"
		class="fixed bottom-4 right-4 left-4 z-[55] sm:w-[360px] sm:left-auto lg:bottom-8 lg:right-8"
	>
        <!-- <ToasterWrapper
			v-for="notification in notifications"
			:key="notification.data.id"
			class="mt-4 overflow-hidden rounded-xl dark:bg-2x-dark-foreground bg-white/80 backdrop-blur-2xl shadow-xl"
			bar-color="bg-theme"
		>
            <Notification :notification="notification" class="z-10 !mb-0 !px-4 !pt-4 !pb-5" />
        </ToasterWrapper> -->

		<ToasterWrapper
			v-for="(toaster, i) in toasters"
			:key="i"
			class="mt-4 overflow-hidden rounded-xl shadow-xl"
			:bar-color="getToasterColor(toaster)"
		>
        	<Toaster :item="toaster" />
        </ToasterWrapper>
    </div>
</template>

<script setup>
import { onMounted, ref } from 'vue'
// import Notification from '../Notifications/Components/Notification'
import ToasterWrapper from './ToasterWrapper.vue'
import { events } from '@/events'
import Toaster from './Toaster.vue'

const toasters = ref([])
const notifications = ref([])

onMounted(() => {
	events.on('toaster', (toaster) => toasters.value.push(toaster))
	// events.on('notification', (notification) => this.notifications.value.push(notification))
})

const getToasterColor = (toaster) => {
	return {
		danger: 'bg-red-400',
		success: 'bg-green-400',
	}[toaster.type]
}

</script>
