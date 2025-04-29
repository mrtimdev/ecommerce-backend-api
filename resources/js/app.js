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

import VueSweetalert2 from 'vue-sweetalert2';
import 'sweetalert2/dist/sweetalert2.min.css';
// import '@sweetalert2/themes/dark/dark.scss'
// import '@sweetalert2/themes/borderless/borderless.scss'

const options = {
  confirmButtonColor: "#7e22ce",
  cancelButtonColor: "#be123c",
  confirmButtonClass: 'focus:outline-none text-white bg-purple-700 hover:bg-purple-800 focus:ring-2 focus:ring-purple-300 font-medium rounded-lg text-sm px-5 py-2.5 mb-2 dark:bg-purple-600 dark:hover:bg-purple-700 dark:focus:ring-purple-900',
  cancelButtonClass: 'focus:outline-none text-white bg-rose-700 hover:bg-rose-800 focus:ring-2 focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-rose-600 dark:hover:bg-rose-700 dark:focus:ring-rose-900',
};

import { i18n } from './i18n';

import ConfirmationModal from '@/Components/ConfirmationModal.vue'
import LoadingIcon from '@/Components/Others/LoadingIcon.vue'
import Modal from '@/Components/Others/Modal.vue'
import Breadcrumb from '@/Components/Others/Breadcrumb.vue'
import CheckBox from '@/components/Others/CheckBox.vue';
import ActionButtons from '@/components/Others/ActionButtons.vue';
import InputError from '@/Components/Others/InputError.vue';
import VueMultiselect from 'vue-multiselect'

import functionHelpers from './helpers/functionHelpers';

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
          .use(VueSweetalert2)
          .use(functionHelpers)
          .component('ConfirmationModal', ConfirmationModal)
          .component('Modal', Modal)
          .component('Bc', Breadcrumb)
          .component('CheckBox', CheckBox)
          .component('ActionButtons', ActionButtons)
          .component('InputError', InputError)
          .component('LoadingIcon', LoadingIcon)
          .component('MultiSelect', VueMultiselect)
          
          .mount(el);
    },
    progress: {
      color: '#7e22ce',
      delay: 250,
      showSpinner: true
    },
});
