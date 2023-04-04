// import './bootstrap';

import { createApp, h } from 'vue';
import { createInertiaApp} from '@inertiajs/inertia-vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { InertiaProgress } from '@inertiajs/progress'
import {numberFormat} from "./functions";
import DefaultLayout from './Layouts/DefaultLayout.vue';
import axios from 'axios';
import Toast from "vue-toastification";
import VueCookies from 'vue-cookies';

window.axios = axios;

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

InertiaProgress.init()

createInertiaApp({
    resolve: (name) => {
        let page = resolvePageComponent(`./Pages/${name}.vue`, import.meta.glob('./Pages/**/**/*.vue'));

        page.then((module) => {
            module.default.layout = module.default.layout || DefaultLayout;
        });

        return page;
    },
    setup({ el, App, props, plugin }) {
        const app = createApp({ render: () => h(App, props) })
            .mixin({ methods: {route, numberFormat}})
            .use(plugin)
            .use(VueCookies, { expires: '1d'})
            .use(Toast, {
                transition: "Vue-Toastification__bounce",
                maxToasts: 20,
                newestOnTop: true,
                position: "top-right",
                timeout: 3000,
                closeOnClick: true,
                pauseOnFocusLoss: true,
                pauseOnHover: true,
                draggable: true,
                draggablePercent: 0.6,
                showCloseButtonOnHover: false,
                hideProgressBar: false,
                closeButton: "button",
                icon: true,
                rtl: false
            })
            .mount(el)

        document.addEventListener('inertia:start', (event) => {
            $('.navbar-collapse').collapse('hide');
        })

        // Hide preloader after app setup
        document.getElementById('preloader-custom').style.display = 'none'
    },
});
