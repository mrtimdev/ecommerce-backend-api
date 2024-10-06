<template>
    <div
        :class="{'overflow-auto': scrollContent, 'overflow-auto': !scrollContent}"
        class="popup-content absolute top-16 bottom-24 left-0 right-0 h-auto px-6 md:relative md:top-0 md:bottom-0"
    >
        <slot />
    </div>
</template>

<script>
    defineProps({
        scrollContent: {
            type: Boolean,
            default: false,
        },
    })
</script>
