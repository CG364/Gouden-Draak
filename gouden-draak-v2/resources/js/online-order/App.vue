<script setup>
import { computed, ref, reactive } from 'vue';

const props = defineProps({
    dishKinds: {
        type: Array,
        default: () => [],
    },
});

function formatPrice(price) {
    return Number(price).toFixed(2);
}

function unitPrice(dish) {
    return dish.discountedPrice ?? dish.price;
}

// Category browsing
const selectedDishKindId = ref(props.dishKinds[0]?.id ?? null);

const activeDishKind = computed(() => props.dishKinds.find((dishKind) => dishKind.id === selectedDishKindId.value) ?? null);

// Cart
const cart = reactive(new Map());
const notes = reactive(new Map());

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
</script>

<template>
    <div>
        <nav class="overflow-x-auto border-b border-gray-200 px-4 py-3">
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

        <div v-if="activeDishKind" class="grid grid-cols-1 gap-4 p-4 sm:grid-cols-2">
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

        <div class="border-t border-gray-200 p-4">
            <h3 class="font-bold text-gray-900">Your order</h3>

            <p v-if="cartLines.length === 0" class="mt-2 text-sm text-gray-500">No products added yet.</p>

            <ul v-else class="mt-2 space-y-2">
                <li v-for="line in cartLines" :key="line.dishId" class="space-y-1 text-sm">
                    <div class="flex items-center justify-between gap-3">
                        <span class="flex-1">{{ line.quantity }}&times; {{ line.dish.name }}</span>
                        <span>&euro; {{ formatPrice(line.lineTotal) }}</span>
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

            <div v-if="cartLines.length > 0" class="mt-3 flex justify-between border-t border-gray-200 pt-3 font-bold text-gray-900">
                <span>Total</span>
                <span>&euro; {{ formatPrice(cartTotal) }}</span>
            </div>

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
                :disabled="cartLines.length === 0"
            >
                Place order ({{ cartCount }} item{{ cartCount === 1 ? '' : 's' }})
            </button>
        </div>
    </div>
</template>
