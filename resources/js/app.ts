import '../css/app.css';
import { createInertiaApp } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { createApp, h, type DefineComponent } from 'vue';
import { initializeTheme } from '@/composables/useAppearance';
import AppLayout from '@/layouts/AppLayout.vue';
import AuthLayout from '@/layouts/AuthLayout.vue';
import SettingsLayout from '@/layouts/settings/Layout.vue';
import { initializeFlashToast } from '@/lib/flashToast';
import PublicLayout from '@/layouts/PublicLayout.vue';
const appName = import.meta.env.VITE_APP_NAME || 'Laravel';

createInertiaApp({
    title: (title) => (title ? `${title} - ${appName}` : appName),
    resolve: (name) =>
        resolvePageComponent(
            `./pages/${name}.vue`,
            import.meta.glob<DefineComponent>('./pages/**/*.vue'),
        ).then((module) => {
            switch (true) {
                case name === 'Landing':
                    module.default.layout = PublicLayout;
                    break;
                case name.startsWith('auth/'):
                    module.default.layout = AuthLayout;
                    break;
                case name.startsWith('settings/'):
                    module.default.layout = [AppLayout, SettingsLayout];
                    break;
                case name.startsWith('inmobiliaria/'):
                case name.startsWith('agente/'):
                case name.startsWith('Cliente/'):
                    // cada página maneja su propio layout via PanelLayout
                    break;
                case name.startsWith('Propiedad/'):
                    module.default.layout = PublicLayout;
                    break;
                default:
                    module.default.layout = AppLayout;
            }

            return module;
        }),
    setup({ el, App, props, plugin }) {
        if (typeof window === 'undefined') return;
        createApp({ render: () => h(App, props) })
            .use(plugin)
            .mount(el);
    },
    progress: {
        color: '#4B5563',
    },
});

// This will set light / dark mode on page load...
initializeTheme();

// This will listen for flash toast data from the server...
initializeFlashToast();
