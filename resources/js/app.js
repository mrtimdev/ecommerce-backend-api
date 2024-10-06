import './bootstrap';
import '../css/satoshi.css';
import '../css/app.css';
import DataTable from 'datatables.net-vue3';
import DataTablesCore from 'datatables.net-dt';
// import Select from 'datatables.net-select';
import 'datatables.net-dt/css/datatables.dataTables.css';
// import 'datatables.net-select-dt';
// import 'datatables.net-responsive-dt';
// import 'datatables.net-bs5'
// import DataTablesCore from 'datatables.net-bs5';

import { createApp, h } from 'vue';
import { createInertiaApp } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { ZiggyVue } from '../../vendor/tightenco/ziggy';
import { createPinia } from 'pinia'
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';
import { library, dom } from '@fortawesome/fontawesome-svg-core';
import { fas } from '@fortawesome/free-solid-svg-icons';
library.add(fas);
dom.watch()

import { i18n } from './i18n';

import ConfirmationModal from '@/Components/ConfirmationModal.vue'
import Modal from '@/Components/Others/Modal.vue'
import CheckBox from '@/components/Others/CheckBox.vue';
import ActionButtons from '@/components/Others/ActionButtons.vue';
import InputError from '@/Components/Others/InputError.vue';

const appName = import.meta.env.VITE_APP_NAME || 'Laravel';

DataTable.use(DataTablesCore);

const loadLocaleFromApi = async () => {
    try {
      const { data } = await axios.get('/admin/locale');
      i18n.global.locale = data.locale;
    } catch (error) {
      console.error('Error loading locale:', error);
    }
};
loadLocaleFromApi();

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name) => resolvePageComponent(`./Pages/${name}.vue`, import.meta.glob('./Pages/**/*.vue')),
    setup({ el, App, props, plugin }) {
        return createApp({ render: () => h(App, props) })
          .use(plugin)
          .use(ZiggyVue)
          .use(createPinia())
          .use(i18n)
          .component('ConfirmationModal', ConfirmationModal)
          .component('Modal', Modal)
          .component('CheckBox', CheckBox)
          .component('ActionButtons', ActionButtons)
          .component('InputError', InputError)
          .mount(el);
    },
    progress: {
        color: '#7e22ce',
        showSpinner: true
    },
});
