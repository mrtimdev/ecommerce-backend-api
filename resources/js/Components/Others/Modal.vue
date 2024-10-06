<script setup>
    import { watch, ref, computed, onMounted, onBeforeUnmount } from 'vue'
    import { onClickOutside } from '@vueuse/core'; 
    import { bus } from '@/eventBus'

    const props = defineProps({
        show: Boolean,
        size: {
            type: String,
                default: 'md', 
                validator: (value) => ['lg', 'md', 'xs'].includes(value) 
        },
        item: {
            type: Object,
            required: false
        },
        buttonConfirmLabel: {
            type: String,
            required: false,
            default: 'confirm'
        },
        buttonCloseLabel: {
            type: String,
            required: false,
            default: 'cancel'
        },
        
        closeOnEscape: {
            type: Boolean,
            default: true
        },
        showConfirmButton: {
            type: Boolean,
            default: false
        },
        showCloseButton: {
            type: Boolean,
            default: true
        },
        showFooter: {
            type: Boolean,
            default: true
        }
    })
    const emit = defineEmits(['update:show', 'confirm', 'close']);
    const target = ref(null)

    watch(() => props.show, (newValue) => {
        if (newValue) {
            console.log('Modal is opened')
        }
    })

    onMounted(() => {
        window.addEventListener('keydown', handleEscape);
    });

    onBeforeUnmount(() => {
        window.removeEventListener('keydown', handleEscape);
    });
    
    
    onClickOutside(target, () => {
        emit('close');
        emit('update:show', false); 
        console.log('Modal is opened', props.show)
        bus.emit('close')
    })
    const handleEscape = (e) => {
        if (e.key === 'Escape') {
            emit('close');
            emit('update:show', false); 
            bus.emit('close')
        }
    };

    const modalClass = computed(() => {
        switch (props.size) {
            case 'lg':
                return 'w-full max-w-4xl';
            case 'md':
                return 'w-full max-w-2xl';
            case 'xs':
                return 'w-full max-w-xs';
            default:
                return 'w-full max-w-md'; 
        }
    });

</script>

<template>
    <Transition name="popup" appear appear-class="bounce">
        <div v-if="show"  class="custom-backdrop-blur popup fixed top-0 left-0 right-0 bottom-0 z-50 grid h-full overflow-y-auto lg:absolute">
            <div ref="target" :class="modalClass" class="fixed top-0 bottom-0 left-0 right-0 z-10 m-auto w-full bg-white shadow-xl dark:bg-dark-foreground md:relative md:rounded-xl">
                <div class="w-full relative max-h-full flex items-center justify-center">
                    <!-- Modal content -->
                    <div class="w-full relative bg-white rounded-lg border border-stroke dark:border-strokedark dark:bg-meta-4">
                        <!-- Modal header -->
                        <div class="flex items-center justify-between p-4 md:p-5 border-b border-stroke rounded-t">
                            <h3 class="text-xl font-semibold text-gray-900 dark:text-white">
                                <slot name="title">Title</slot>
                            </h3>
                            <button @click="$emit('close')" type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white" data-modal-hide="static-modal">
                                <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                                </svg>
                                <span class="sr-only">Close modal</span>
                            </button>
                        </div>
                        <!-- Modal body -->
                        <div class="modal-body p-4 md:p-5 space-y-4">
                            <slot name="body">
                                <p class="text-base leading-relaxed text-gray-500 dark:text-gray-400">
                                    With less than a month to go before the European Union enacts new consumer privacy laws for its citizens, companies around the world are updating their terms of service agreements to comply.
                                </p>
                                <p class="text-base leading-relaxed text-gray-500 dark:text-gray-400">
                                    The European Union’s General Data Protection Regulation (G.D.P.R.) goes into effect on May 25 and is meant to ensure a common set of data rights in the European Union. It requires organizations to notify users as soon as possible of high-risk data breaches that could personally affect them.
                                </p>
                            </slot>
                        </div>
                        <!-- Modal footer -->
                        <div v-if="showFooter" class="modal-footer p-4 md:p-5 space-y-4">
                            <slot name="footer">
                                <div class="flex justify-center gap-5 items-center border-t border-stroke rounded-b">
                                    <button v-if="showConfirmButton" @click="$emit('confirm')" type="button" class="w-full text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">{{ $t(buttonConfirmLabel) }}</button>
                                    <button v-if="showCloseButton" @click="$emit('close')" type="button" class="w-full focus:outline-none text-white bg-rose-700 hover:bg-rose-800 focus:ring-4 focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5 dark:bg-rose-600 dark:hover:bg-rose-700 dark:focus:ring-rose-900">{{ $t(buttonCloseLabel) }}</button>
                                </div>
                            </slot>
                        </div>
                    </div>
                </div>
            </div>
        </div>
  </Transition>
</template>

<style scoped lang="scss">
    
    .custom-backdrop-blur {
        --tw-backdrop-blur: blur(2px);
        -webkit-backdrop-filter: var(--tw-backdrop-blur) var(--tw-backdrop-brightness) var(--tw-backdrop-contrast) var(--tw-backdrop-grayscale) var(--tw-backdrop-hue-rotate) var(--tw-backdrop-invert) var(--tw-backdrop-opacity) var(--tw-backdrop-saturate) var(--tw-backdrop-sepia);
        backdrop-filter: var(--tw-backdrop-blur) var(--tw-backdrop-brightness) var(--tw-backdrop-contrast) var(--tw-backdrop-grayscale) var(--tw-backdrop-hue-rotate) var(--tw-backdrop-invert) var(--tw-backdrop-opacity) var(--tw-backdrop-saturate) var(--tw-backdrop-sepia);
    }
    .popup-leave-active {
        animation: popup-slide-in 0.15s ease reverse;
    }

    @media only screen and (min-width: 960px) {
        .popup-enter-active {
        animation: popup-slide-in 0.25s 0.1s ease both;
  }

  @keyframes popup-slide-in {
    0% {
      opacity: 0;
      transform: translateY(-100px);
    }

    100% {
      opacity: 1;
      transform: translateY(0);
    }
  }
}

@media only screen and (max-width: 960px) {
  .popup-enter-active {
    animation: popup-slide-in 0.35s 0.15s ease both;
  }

  @keyframes popup-slide-in {
    0% {
      opacity: 0;
      transform: translateY(200px);
    }
    100% {
      opacity: 1;
      transform: translateY(0);
    }
  }
}

.bounce {
  animation: bounce-in 0.5s;
}

@keyframes bounce-in {
  0% {
    transform: scale(0);
  }
  50% {
    transform: scale(1.5);
  }
  100% {
    transform: scale(1);
  }
}
</style>