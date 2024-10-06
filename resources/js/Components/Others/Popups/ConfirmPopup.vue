<template>
    <PopupWrapper>
        <div class="flex h-full transform items-center justify-center px-8 text-center md:translate-y-0">
            <div v-if="item">
                <h1 class="mt-5 mb-2 text-2xl font-bold">
                    {{ $t('a_u_sure') }}
                </h1>
                <p v-if="action || item.action" class="mb-4 text-sm">
                    {{ item.action || action }} - {{ item.data.name }}
                </p>
            </div>
            <div v-else>
                <h1 class="mt-5 mb-2 text-2xl font-bold">
                    {{ $t('item_not_found') }}
                </h1>
                <p class="mb-4 text-sm">
                    {{ $t('please_try_again') }}
                </p>
            </div>
        </div>

        <PopupActions>
            <button @click.native="close" type="button" class="w-full focus:outline-none text-white bg-rose-700 hover:bg-rose-800 focus:ring-4 focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-rose-600 dark:hover:bg-rose-700 dark:focus:ring-rose-900">
                {{ $t('cancel') }}
            </button>
            <button @click.native="confirm" :disabled="!item || !item.data" type="button" class="w-full focus:outline-none text-white bg-purple-700 hover:bg-purple-800 focus:ring-4 focus:ring-purple-300 font-medium rounded-lg text-sm px-5 py-2.5 mb-2 dark:bg-purple-600 dark:hover:bg-purple-700 dark:focus:ring-purple-900">
                {{ $t('yes_iam_sure') }}
            </button>
        </PopupActions>
    </PopupWrapper>
</template>

<script setup>
    import PopupWrapper from './Components/PopupWrapper.vue'
    import PopupActions from './Components/PopupActions.vue'
    import { useEventBus } from '@vueuse/core'; 
    import { ref, onMounted, onBeforeUnmount } from 'vue';

    const action = ref('Action');
    const item = ref(false)
    const { emit: closePopup } = useEventBus('popup:close');
    const { emit: actionConfirmed } = useEventBus('action:confirmed');
    const { on: onConfirmOpen } = useEventBus('confirm:open');
    
    const close = () => {
        item.value = false
        closePopup({})
    };
    
    const confirm = () => {
        if (!item.value) return
        actionConfirmed(item.value.data, item.value.action)
    };

    const handleEnter = (event) => {
        if (event.key === 'Enter') {
            confirm()
        }
    }
    const handleEscape = (e) => {
        if (e.key === 'Escape') {
            close()
        }
    };
    
    onMounted(() => {
        onConfirmOpen((args) => {
            item.value = args
            console.log({item})
        });
        window.addEventListener('keydown', handleEscape)
        window.addEventListener('keydown', handleEnter)
    })
    onBeforeUnmount(() => {
        window.removeEventListener('keydown', handleEscape)
        window.removeEventListener('keydown', handleEnter)
    })
</script>
