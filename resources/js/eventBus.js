import { useEventBus } from '@vueuse/core'

const fooKey = Symbol('symbol-key')

export const bus = useEventBus(fooKey)
