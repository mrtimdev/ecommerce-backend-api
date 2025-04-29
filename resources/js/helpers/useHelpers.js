import { getCurrentInstance } from 'vue';

export function useHelpers() {
    const { proxy } = getCurrentInstance();
    return {
        isRole: proxy.$isRole,
        isPermission: proxy.$isPermission,
    };
}