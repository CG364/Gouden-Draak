<script setup>
import { computed, reactive, ref, watch, onMounted, onUnmounted } from 'vue';

const props = defineProps({
    dishKinds: {
        type: Array,
        default: () => [],
    },
    tableNr: {
        type: Number,
        required: true,
    },
    maxRounds: {
        type: Number,
        required: true,
    },
    roundsRemaining: {
        type: Number,
        required: true,
    },
    nextOrderAvailableAt: {
        type: String,
        default: null,
    },
    pastOrders: {
        type: Array,
        default: () => [],
    },
    status: {
        type: String,
        default: null,
    },
    csrfToken: {
        type: String,
        required: true,
    },
    sessionStatusUrl: {
        type: String,
        required: true,
    },
    serviceRequestActionUrl: {
        type: String,
        required: true,
    },
    serviceRequestStatusUrl: {
        type: String,
        required: true,
    },
    hasPendingServiceRequest: {
        type: Boolean,
        default: false,
    },
});

// Local, mutable copies of server-flashed state: the initial values come from
// props, but polling below needs to be able to clear them once a waiter
// handles the call, without a full page reload.
const statusMessage = ref(props.status);
const hasPendingServiceRequest = ref(props.hasPendingServiceRequest);

function formatPrice(price) {
    return Number(price).toFixed(2);
}

function unitPrice(dish) {
    return dish.discountedPrice ?? dish.price;
}

// Live cooldown countdown
const now = ref(Date.now());
let timer = null;

onMounted(() => {
    timer = setInterval(() => {
        now.value = Date.now();
    }, 1000);
});

onUnmounted(() => {
    if (timer) {
        clearInterval(timer);
    }
});

// The dining session may be closed by a waiter at any time while the tablet
// is still open on the menu. Once detected, the ordering UI is replaced with
// a friendly "session ended" screen instead of letting further actions fail
// with a raw error.
const sessionEnded = ref(false);
const SESSION_STATUS_POLL_INTERVAL_MS = 20000;
let sessionStatusPollTimer = null;

async function pollSessionStatus() {
    try {
        const response = await fetch(props.sessionStatusUrl, {
            headers: { Accept: 'application/json' },
        });

        if (response.status === 410) {
            sessionEnded.value = true;
        }
    } catch {
        // Ignore network hiccups; the next poll will try again.
    }
}

onMounted(() => {
    sessionStatusPollTimer = setInterval(pollSessionStatus, SESSION_STATUS_POLL_INTERVAL_MS);
});

onUnmounted(() => {
    if (sessionStatusPollTimer) {
        clearInterval(sessionStatusPollTimer);
    }
});

// Calling the waiter is done via fetch rather than a normal form submission
// (unlike placing an order), so it doesn't trigger a full page reload that
// would wipe out whatever the customer is still assembling in the cart.
const callingWaiter = ref(false);

async function callWaiter() {
    if (hasPendingServiceRequest.value || callingWaiter.value) {
        return;
    }

    callingWaiter.value = true;

    try {
        const response = await fetch(props.serviceRequestActionUrl, {
            method: 'POST',
            headers: {
                Accept: 'application/json',
                'X-CSRF-TOKEN': props.csrfToken,
            },
        });

        if (response.status === 410) {
            sessionEnded.value = true;

            return;
        }

        if (response.ok) {
            const data = await response.json();
            hasPendingServiceRequest.value = true;
            statusMessage.value = data.status;
        }
    } finally {
        callingWaiter.value = false;
    }
}

// While a waiter call is pending, poll every so often to find out once a
// waiter marks it handled, so the "waiter is on the way" state and flash
// message clear themselves instead of lingering until the next page load.
const SERVICE_REQUEST_POLL_INTERVAL_MS = 10000;
let serviceRequestPollTimer = null;

async function pollServiceRequestStatus() {
    try {
        const response = await fetch(props.serviceRequestStatusUrl, {
            headers: { Accept: 'application/json' },
        });

        if (response.status === 410) {
            sessionEnded.value = true;

            return;
        }

        if (!response.ok) {
            return;
        }

        const data = await response.json();

        if (!data.hasPendingServiceRequest) {
            hasPendingServiceRequest.value = false;
            statusMessage.value = null;
        }
    } catch {
        // Ignore network hiccups; the next poll will try again.
    }
}

watch(hasPendingServiceRequest, (isPending) => {
    if (isPending) {
        serviceRequestPollTimer ??= setInterval(pollServiceRequestStatus, SERVICE_REQUEST_POLL_INTERVAL_MS);
    } else if (serviceRequestPollTimer) {
        clearInterval(serviceRequestPollTimer);
        serviceRequestPollTimer = null;
    }
}, { immediate: true });

onUnmounted(() => {
    if (serviceRequestPollTimer) {
        clearInterval(serviceRequestPollTimer);
    }
});

// Flash messages that aren't tied to an in-progress waiter call (e.g. "Order
// placed.") should disappear on their own instead of lingering until the
// next full page load. A pending call's message is left alone here since the
// poll above already clears it, in sync with the "waiter is on the way"
// button state, once a waiter actually handles it.
const STATUS_MESSAGE_TIMEOUT_MS = 6000;
let statusMessageTimer = null;

watch(statusMessage, (message) => {
    if (statusMessageTimer) {
        clearTimeout(statusMessageTimer);
        statusMessageTimer = null;
    }

    if (message && !hasPendingServiceRequest.value) {
        statusMessageTimer = setTimeout(() => {
            statusMessage.value = null;
        }, STATUS_MESSAGE_TIMEOUT_MS);
    }
}, { immediate: true });

onUnmounted(() => {
    if (statusMessageTimer) {
        clearTimeout(statusMessageTimer);
    }
});

// Once the session has ended, none of the polling or countdown timers serve
// any further purpose.
watch(sessionEnded, (ended) => {
    if (!ended) {
        return;
    }

    [sessionStatusPollTimer, serviceRequestPollTimer, timer].forEach((intervalId) => {
        if (intervalId) {
            clearInterval(intervalId);
        }
    });

    sessionStatusPollTimer = null;
    serviceRequestPollTimer = null;
    timer = null;

    if (statusMessageTimer) {
        clearTimeout(statusMessageTimer);
        statusMessageTimer = null;
    }
});

const availableAt = props.nextOrderAvailableAt ? new Date(props.nextOrderAvailableAt).getTime() : null;

const cooldownSecondsRemaining = computed(() => {
    if (!availableAt) {
        return 0;
    }

    return Math.max(0, Math.ceil((availableAt - now.value) / 1000));
});

const onCooldown = computed(() => cooldownSecondsRemaining.value > 0);

const cooldownDisplay = computed(() => {
    const totalSeconds = cooldownSecondsRemaining.value;
    const minutes = Math.floor(totalSeconds / 60);
    const seconds = totalSeconds % 60;

    return `${minutes}:${String(seconds).padStart(2, '0')}`;
});

const roundsUsed = props.maxRounds - props.roundsRemaining;
const roundsExhausted = props.roundsRemaining <= 0;
const canOrderNow = computed(() => !roundsExhausted && !onCooldown.value);

// Category browsing
const selectedDishKindId = ref(props.dishKinds[0]?.id ?? null);

const activeDishKind = computed(() => props.dishKinds.find((dishKind) => dishKind.id === selectedDishKindId.value) ?? null);

// Cart
const cart = reactive(new Map());
const notes = reactive(new Map());
const showCart = ref(false);

const allDishes = computed(() => props.dishKinds.flatMap((dishKind) => dishKind.dishes));

function quantityFor(dishId) {
    return cart.get(dishId) ?? 0;
}

function increment(dish) {
    cart.set(dish.id, quantityFor(dish.id) + 1);
}

function decrement(dish) {
    const next = quantityFor(dish.id) - 1;

    if (next <= 0) {
        cart.delete(dish.id);
        notes.delete(dish.id);
    } else {
        cart.set(dish.id, next);
    }
}

function setNote(dishId, value) {
    if (value.trim() === '') {
        notes.delete(dishId);
    } else {
        notes.set(dishId, value);
    }
}

const cartLines = computed(() => Array.from(cart.entries()).map(([dishId, quantity]) => {
    const dish = allDishes.value.find((candidate) => candidate.id === dishId);

    return {
        dishId,
        quantity,
        dish,
        lineTotal: unitPrice(dish) * quantity,
    };
}));

const cartCount = computed(() => cartLines.value.reduce((sum, line) => sum + line.quantity, 0));
const cartTotal = computed(() => cartLines.value.reduce((sum, line) => sum + line.lineTotal, 0));

function formatTime(iso) {
    return new Date(iso).toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
}

function reorder(order) {
    order.items.forEach((item) => {
        const dish = allDishes.value.find((candidate) => candidate.id === item.dishId);

        if (dish) {
            cart.set(dish.id, quantityFor(dish.id) + item.quantity);

            if (item.notes && !notes.has(dish.id)) {
                notes.set(dish.id, item.notes);
            }
        }
    });

    showCart.value = true;
}
</script>

<template>
    <div v-if="sessionEnded" class="flex min-h-screen flex-col items-center justify-center px-6 text-center">
        <p class="text-2xl font-bold text-gray-900">This table session has ended</p>
        <p class="mt-2 text-gray-600">Thank you for visiting! Please flag down a member of staff if you need anything else.</p>
    </div>

    <div v-else class="min-h-screen pb-28">
        <header class="sticky top-0 z-10 bg-red-800 text-white shadow">
            <div class="mx-auto flex max-w-5xl items-center justify-between gap-4 px-4 py-3">
                <div>
                    <p class="text-xs uppercase tracking-wide text-red-200">De Gouden Draak</p>
                    <p class="text-3xl font-bold leading-tight">Table {{ tableNr }}</p>
                </div>

                <button
                    type="button"
                    class="shrink-0 rounded-full border border-red-300 px-4 py-2 text-sm font-semibold disabled:cursor-not-allowed disabled:opacity-60"
                    :disabled="hasPendingServiceRequest || callingWaiter"
                    @click="callWaiter"
                >
                    {{ hasPendingServiceRequest ? 'Waiter is on the way' : 'Call waiter' }}
                </button>

                <div class="text-right">
                    <p class="text-sm font-medium">Round {{ Math.min(roundsUsed + 1, maxRounds) }} / {{ maxRounds }}</p>
                    <p v-if="onCooldown" class="text-xs text-red-200">
                        Next order in {{ cooldownDisplay }}
                    </p>
                    <p v-else-if="roundsExhausted" class="text-xs text-red-200">
                        No rounds left
                    </p>
                </div>
            </div>
        </header>

        <div v-if="statusMessage" class="mx-auto max-w-5xl px-4 pt-4">
            <div class="rounded border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-800">
                {{ statusMessage }}
            </div>
        </div>

        <nav class="sticky top-[72px] z-10 overflow-x-auto border-b border-gray-200 bg-white px-4 py-3">
            <div class="flex gap-2">
                <button
                    v-for="dishKind in dishKinds"
                    :key="dishKind.id"
                    type="button"
                    class="whitespace-nowrap rounded-full px-4 py-2 text-sm font-semibold"
                    :class="selectedDishKindId === dishKind.id ? 'bg-red-800 text-white' : 'bg-gray-100 text-gray-700'"
                    @click="selectedDishKindId = dishKind.id"
                >
                    {{ dishKind.name }}
                </button>
            </div>
        </nav>

        <main class="mx-auto max-w-5xl px-4 py-5">
            <div v-if="activeDishKind" class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                <div
                    v-for="dish in activeDishKind.dishes"
                    :key="dish.id"
                    class="flex items-center justify-between gap-3 rounded-lg border border-gray-200 bg-white p-4 shadow-sm"
                >
                    <div class="min-w-0">
                        <p class="font-bold text-gray-900">
                            <span class="pr-2 text-gray-400">{{ dish.menuNumber }}</span>{{ dish.name }}
                        </p>
                        <p v-if="dish.description" class="truncate text-sm text-gray-500">{{ dish.description }}</p>
                        <p class="mt-1 text-sm font-semibold" :class="{ 'text-red-600': dish.discountedPrice !== null }">
                            &euro; {{ formatPrice(unitPrice(dish)) }}
                            <span v-if="dish.discountedPrice !== null" class="font-normal text-gray-400 line-through">
                                &euro; {{ formatPrice(dish.price) }}
                            </span>
                        </p>
                    </div>

                    <div class="flex shrink-0 items-center gap-3">
                        <button
                            type="button"
                            class="h-10 w-10 rounded-full bg-gray-100 text-xl font-bold text-gray-700 disabled:opacity-30"
                            :disabled="quantityFor(dish.id) === 0"
                            @click="decrement(dish)"
                        >
                            &minus;
                        </button>
                        <span class="w-6 text-center text-lg font-semibold">{{ quantityFor(dish.id) }}</span>
                        <button
                            type="button"
                            class="h-10 w-10 rounded-full bg-red-800 text-xl font-bold text-white"
                            @click="increment(dish)"
                        >
                            +
                        </button>
                    </div>
                </div>
            </div>

            <section v-if="pastOrders.length > 0" class="mt-10">
                <h2 class="mb-3 text-lg font-bold text-gray-900">Your orders so far</h2>
                <div class="space-y-2">
                    <div v-for="order in pastOrders" :key="order.id" class="flex items-center justify-between gap-3 rounded-lg border border-gray-200 bg-white p-3 text-sm">
                        <div class="min-w-0">
                            <p class="font-semibold text-gray-700">{{ formatTime(order.placedAt) }}</p>
                            <p class="text-gray-500">
                                <span v-for="(item, index) in order.items" :key="index">
                                    {{ item.quantity }}&times; {{ item.name }}<span v-if="index < order.items.length - 1">, </span>
                                </span>
                            </p>
                        </div>
                        <button
                            type="button"
                            class="shrink-0 rounded-full border border-gray-300 px-3 py-1.5 text-xs font-semibold text-gray-700"
                            @click="reorder(order)"
                        >
                            Reorder
                        </button>
                    </div>
                </div>
            </section>
        </main>

        <!-- Cart bar -->
        <div class="fixed inset-x-0 bottom-0 z-20 border-t border-gray-200 bg-white shadow-lg">
            <button
                v-if="!showCart"
                type="button"
                class="flex w-full items-center justify-between px-4 py-4"
                :disabled="cartCount === 0"
                @click="showCart = true"
            >
                <span class="text-sm font-semibold text-gray-700">
                    {{ cartCount }} item(s) - &euro; {{ formatPrice(cartTotal) }}
                </span>
                <span class="rounded bg-red-800 px-4 py-2 text-sm font-bold text-white" :class="{ 'opacity-30': cartCount === 0 }">
                    View order
                </span>
            </button>

            <div v-else class="max-h-[70vh] overflow-y-auto p-4">
                <div class="mb-3 flex items-center justify-between">
                    <h3 class="text-lg font-bold text-gray-900">Your order</h3>
                    <button type="button" class="text-sm text-gray-500" @click="showCart = false">Close</button>
                </div>

                <p v-if="cartLines.length === 0" class="py-6 text-center text-sm text-gray-500">No products added yet.</p>

                <ul v-else class="space-y-3">
                    <li v-for="line in cartLines" :key="line.dishId" class="space-y-1">
                        <div class="flex items-center justify-between gap-3">
                            <span class="flex-1 text-sm font-medium">{{ line.quantity }}&times; {{ line.dish.name }}</span>
                            <span class="text-sm">&euro; {{ formatPrice(line.lineTotal) }}</span>
                            <button type="button" class="text-red-600" @click="decrement(line.dish)">&times;</button>
                        </div>
                        <input
                            type="text"
                            maxlength="255"
                            placeholder="Note (e.g. no onions)"
                            class="w-full rounded border-gray-300 text-xs shadow-sm focus:border-red-500 focus:ring-red-500"
                            :value="notes.get(line.dishId) ?? ''"
                            @input="setNote(line.dishId, $event.target.value)"
                        >
                    </li>
                </ul>

                <div v-if="cartLines.length > 0" class="mt-4 flex justify-between border-t border-gray-200 pt-3 font-bold text-gray-900">
                    <span>Total</span>
                    <span>&euro; {{ formatPrice(cartTotal) }}</span>
                </div>

                <p v-if="!canOrderNow" class="mt-3 text-center text-sm text-red-600">
                    <span v-if="roundsExhausted">You've reached the maximum of {{ maxRounds }} rounds for this table.</span>
                    <span v-else>You can place your next order in {{ cooldownDisplay }}.</span>
                </p>

                <input
                    v-for="line in cartLines"
                    :key="`quantity-${line.dishId}`"
                    type="hidden"
                    :name="`quantities[${line.dishId}]`"
                    :value="line.quantity"
                >
                <input
                    v-for="line in cartLines"
                    :key="`note-${line.dishId}`"
                    type="hidden"
                    :name="`notes[${line.dishId}]`"
                    :value="notes.get(line.dishId) ?? ''"
                >

                <button
                    type="submit"
                    class="mt-4 w-full rounded bg-red-800 px-4 py-3 text-base font-bold text-white disabled:cursor-not-allowed disabled:opacity-40"
                    :disabled="cartLines.length === 0 || !canOrderNow"
                >
                    Place order
                </button>
            </div>
        </div>
    </div>
</template>
