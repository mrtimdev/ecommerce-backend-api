import axios from 'axios';
import NProgress from "nprogress";
window.axios = axios;
import $ from 'jquery';
window.$ = $;
window.NProgress = NProgress;
window.jQuery = $;
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
