
import './bootstrap';
// import 'normalize.css';
// import '../css/preflight.css';
import '../css/app.css';
import 'element-plus/dist/index.css'
import 'element-tiptap-vue3-fixed/lib/style.css'
import 'element-plus/theme-chalk/display.css'
import { createApp, h } from 'vue';
import { createInertiaApp } from '@inertiajs/vue3';
// import { ZiggyVue } from 'ziggy-js'
import ElemenPlus from 'element-plus'
import ELementTiptapPlugin from 'element-tiptap-vue3-fixed';
import VueExcelEditor from 'vue3-excel-editor';
import { setupCalendar } from 'v-calendar';

import DashboardLayout from './layouts/DashboardLayout.vue';
createInertiaApp({
    progress: true, 
    resolve: name => {
        const pages = import.meta.glob('./Pages/**/*.vue', { eager: true})
        const page = pages[`./Pages/${name}.vue`]
        // return pages[`./Pages/${name}.vue`]
        if (name.startsWith('Dashboard/')) {
            page.default.layout = page.default.layout || DashboardLayout
        }

        return page
    },
    setup({ el, App, props, plugin}) {
        createApp({ render: () => h(App, props)})
            .use(plugin)
            // .use(ZiggyVue)
            .use(ElemenPlus, {size: 'small'})
            .use(ELementTiptapPlugin)
            .use(VueExcelEditor)
            .use(setupCalendar, {})
            .mixin({ 
                methods: {
                     appRoute: route ,
                     navigate(url) {
                        router.get(url, {}, {
                            preserveScroll: true,
                            preserveState: false
                        })
                     }
                    }
                })
            .mount(el)
    }
})
