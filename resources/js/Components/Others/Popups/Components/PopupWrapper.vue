<template>
    <transition name="popup" appear appear-class="bounce">
      <div
        
        v-if="isVisible"
        :class="{'is-mobile-view popup-wrapper p-2': isMobile(), 'p-10': !isMobile()}"
        class="custom-backdrop-blur popup fixed top-0 left-0 right-0 bottom-0 z-50 grid h-full overflow-y-auto lg:absolute"
      >
        <div ref="target"
          class="fixed top-0 bottom-0 left-0 right-0 z-10 m-auto w-full bg-white shadow-xl dark:bg-dark-foreground md:relative md:w-[490px] md:rounded-xl"
          :class="{'mobile-popup-body rounded-md': isMobile()}"
        >
          <slot />
        </div>
      </div>
    </transition>
  </template>
  
<script setup>
    import { ref, onMounted } from 'vue';
    import { onClickOutside } from '@vueuse/core'; 
    import { events } from '@/events'; 

    
    const props = defineProps({
        name: String,
        backdropClick: {
          type: Boolean,
          default: false
        },
    });
    
    const target = ref(null)
    const isVisible = ref(false);
    
    onClickOutside(target, () => {
      if(props.backdropClick) {
        isVisible.value = true
      } else {
        isVisible.value = false
      }
      
    })
    
    const isMobile = () => {
        return window.innerWidth <= 960;
    };

    
    
    onMounted(() => {
        events.on('popup:open', (data) => {
            isVisible.value = true;
        });
        
        events.on('confirm:open', (data) => {
          isVisible.value = true;
        });
        events.on('confirm:cancel', (data) => {
          isVisible.value = false;
        });

        events.on('popup:close', () => (isVisible.value = false));
    });
</script>
  
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
  