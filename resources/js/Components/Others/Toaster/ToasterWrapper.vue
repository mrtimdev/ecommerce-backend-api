<template>
	<transition appear name="popup">
		<div v-if="isActive"  class="relative">
			<div @click="isActive = false" class="absolute z-[20] right-0 top-[-5px] cursor-pointer p-2">
				<i class="fi fi-rs-cross text-xs !text-black opacity-1" />
			</div>

			<slot />

			<!--Progress bar-->
			<div class="absolute bottom-0 left-0 right-0 z-20">
				<span class="bar-animation block h-1 w-0" :class="barColor"></span>
			</div>
		</div>
	</transition>
</template>

<script setup>
	import { onMounted, ref } from 'vue'
	const isActive = ref(true)
	defineProps({
		barColor: String
	})

	onMounted(() => {
		setTimeout(() => (isActive.value = false), 6000)
	})
</script>

<style lang="scss" scoped>
.bar-animation {
	animation: progressbar 6s linear both;
}

@keyframes progressbar {
	0% {
		width: 0;
	}
	100% {
		width: 100%;
	}
}
.popup-leave-active {
    animation: animate_leave 0.15s ease both;
}
.popup-enter-active {
	animation: animate_enter 0.25s 0.1s ease both;
}
.popup-enter-active,
.popup-leave-active {
	transition: opacity 0.3s ease, transform 0.3s ease;
}

@keyframes animate_leave {
	0% {
	  opacity: .5;
	  transform: translateX(100px);
	}

	100% {
	  opacity: 0;
	  transform: translateX(100px);
	}
}
@keyframes animate_enter {
	0% {
	  opacity: 0;
	  transform: translateX(100px);
	}

	100% {
	  opacity: 1;
	  transform: translateY(0);
	}
}
</style>