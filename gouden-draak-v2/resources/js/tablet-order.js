import { createApp } from 'vue';
import TabletOrderApp from './tablet-order/App.vue';

const el = document.getElementById('tablet-order-app');

if (el) {
    const dishKinds = JSON.parse(el.dataset.dishKinds ?? '[]');
    const pastOrders = JSON.parse(el.dataset.pastOrders ?? '[]');

    createApp(TabletOrderApp, {
        dishKinds,
        tableNr: Number(el.dataset.tableNr),
        maxRounds: Number(el.dataset.maxRounds),
        roundsRemaining: Number(el.dataset.roundsRemaining),
        nextOrderAvailableAt: el.dataset.nextOrderAvailableAt || null,
        pastOrders,
        status: el.dataset.status || null,
        csrfToken: el.dataset.csrfToken,
        sessionStatusUrl: el.dataset.sessionStatusUrl,
        serviceRequestActionUrl: el.dataset.serviceRequestActionUrl,
        serviceRequestStatusUrl: el.dataset.serviceRequestStatusUrl,
        hasPendingServiceRequest: el.dataset.hasPendingServiceRequest === '1',
    }).mount(el);
}
