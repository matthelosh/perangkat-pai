
import './bootstrap';
// import 'normalize.css';
import '../css/preflight.css';
import '../css/app.css';
import 'element-plus/dist/index.css'
import 'element-tiptap-vue3-fixed/lib/style.css'
import { createApp, h } from 'vue';
import { createInertiaApp } from '@inertiajs/vue3';
// import { ZiggyVue } from 'ziggy-js'
import ElemenPlus from 'element-plus'
import ELementTiptapPlugin from 'element-tiptap-vue3-fixed';
import VueExcelEditor from 'vue3-excel-editor';
import { setupCalendar } from 'v-calendar';

createInertiaApp({
    progress: false, 
    resolve: name => {
        const pages = import.meta.glob('./Pages/**/*.vue', { eager: true})
        return pages[`./Pages/${name}.vue`]
    },
    setup({ el, App, props, plugin}) {
        createApp({ render: () => h(App, props)})
            .use(plugin)
            // .use(ZiggyVue)
            .use(ElemenPlus, {size: 'small'})
            .use(ELementTiptapPlugin)
            .use(VueExcelEditor)
            .use(setupCalendar, {})
            .mixin({ methods: { appRoute: route }})
            .mount(el)
    }
})
