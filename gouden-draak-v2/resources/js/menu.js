import { createApp } from 'vue';
import MenuApp from './menu/App.vue';

const el = document.getElementById('menu-app');

if (el) {
    const dishKinds = JSON.parse(el.dataset.dishKinds ?? '[]');
    const translations = JSON.parse(el.dataset.translations ?? '{}');
    const locale = el.dataset.locale ?? 'nl';

    createApp(MenuApp, { dishKinds, translations, locale }).mount(el);
}
