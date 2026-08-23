import { createApp } from 'vue';
import OrderApp from './order/App.vue';

const el = document.getElementById('order-app');

if (el) {
    const dishes = JSON.parse(el.dataset.dishes ?? '[]');
    const dishKinds = JSON.parse(el.dataset.dishKinds ?? '[]');

    createApp(OrderApp, { dishes, dishKinds }).mount(el);
}
