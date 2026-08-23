import { createApp } from 'vue';
import OnlineOrderApp from './online-order/App.vue';

const el = document.getElementById('online-order-app');

if (el) {
    const dishKinds = JSON.parse(el.dataset.dishKinds ?? '[]');

    createApp(OnlineOrderApp, { dishKinds }).mount(el);
}
