import { createInertiaApp } from '@inertiajs/vue3';
import { createApp, h } from 'vue';
import AppLayout from './Pages/Layouts/AppLayout.vue';

createInertiaApp({
    resolve: (name) => {
        const pages = import.meta.glob<any>('./Pages/**/*.vue', { eager: true });
        let page = pages[`./Pages/${name}.vue`];

        if (!page) {
            // Check for case-insensitive match in keys
            const key = Object.keys(pages).find(k => k.toLowerCase() === `./pages/${name.toLowerCase()}.vue`);

            if (key) {
                page = pages[key];
            }
        }

        if (!page) {
            throw new Error(`Page not found: ./Pages/${name}.vue`);
        }

        const component = page.default;

        if (component) {
            component.layout ??= AppLayout;
        }

        return page;
    },
    setup({ el, App, props, plugin }) {
        createApp({ render: () => h(App, props) })
            .use(plugin)
            .mount(el);
    },
    progress: {
        color: '#4B5563',
    },
});
