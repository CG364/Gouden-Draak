<script setup>
import { computed, reactive, ref } from 'vue';

const props = defineProps({
    dishes: {
        type: Array,
        default: () => [],
    },
    dishKinds: {
        type: Array,
        default: () => [],
    },
});

const search = ref('');
const selectedDishKindId = ref('');
const cart = reactive(new Map());
const notes = reactive(new Map());

function formatPrice(price) {
    return Number(price).toFixed(2);
}

function unitPrice(dish) {
    return dish.discountedPrice ?? dish.price;
}

const filteredDishes = computed(() => {
    const query = search.value.trim().toLowerCase();

    return props.dishes.filter((dish) => {
        const matchesKind = !selectedDishKindId.value || String(dish.dishKindId) === String(selectedDishKindId.value);
        const matchesQuery = !query
            || dish.name.toLowerCase().includes(query)
            || dish.menuNumber.toLowerCase().includes(query);

        return matchesKind && matchesQuery;
    });
});

function addToCart(dish) {
    cart.set(dish.id, (cart.get(dish.id) ?? 0) + 1);
}

function setQuantity(dishId, value) {
    const quantity = Math.floor(Number(value) || 0);

    if (quantity <= 0) {
        cart.delete(dishId);
        notes.delete(dishId);
    } else {
        cart.set(dishId, quantity);
    }
}

function removeFromCart(dishId) {
    cart.delete(dishId);
    notes.delete(dishId);
}

function setNote(dishId, value) {
    if (value.trim() === '') {
        notes.delete(dishId);
    } else {
        notes.set(dishId, value);
    }
}

const cartLines = computed(() => Array.from(cart.entries()).map(([dishId, quantity]) => {
    const dish = props.dishes.find((candidate) => candidate.id === dishId);

    return {
        dishId,
        quantity,
        dish,
        lineTotal: unitPrice(dish) * quantity,
    };
}));

const cartTotal = computed(() => cartLines.value.reduce((sum, line) => sum + line.lineTotal, 0));
</script>

<template>
    <div class="grid gap-6 lg:grid-cols-3">
        <div class="space-y-4 lg:col-span-2">
            <div class="flex flex-wrap gap-3">
                <input
                    v-model="search"
                    type="text"
                    placeholder="Search by name or menu number..."
                    class="flex-1 min-w-[12rem] rounded border-gray-300 shadow-sm focus:border-gray-500 focus:ring-gray-500"
                >

                <select
                    v-model="selectedDishKindId"
                    class="rounded border-gray-300 shadow-sm focus:border-gray-500 focus:ring-gray-500"
                >
                    <option value="">All categories</option>
                    <option v-for="dishKind in dishKinds" :key="dishKind.id" :value="dishKind.id">
                        {{ dishKind.name }}
                    </option>
                </select>
            </div>

            <ul class="max-h-[32rem] divide-y divide-gray-200 overflow-y-auto rounded-lg border border-gray-200">
                <li
                    v-for="dish in filteredDishes"
                    :key="dish.id"
                    class="flex items-center justify-between gap-4 px-4 py-2"
                >
                    <div>
                        <p class="font-bold">
                            <span class="pr-2 text-gray-400">{{ dish.menuNumber }}</span>{{ dish.name }}
                        </p>
                        <p class="text-sm" :class="{ 'font-bold text-red-600': dish.discountedPrice !== null }">
                            &euro; {{ formatPrice(unitPrice(dish)) }}
                            <span v-if="dish.discountedPrice !== null" class="font-normal text-gray-400 line-through">
                                &euro; {{ formatPrice(dish.price) }}
                            </span>
                        </p>
                    </div>

                    <button
                        type="button"
                        class="rounded bg-gray-900 px-3 py-1 text-sm font-medium text-white hover:bg-gray-700"
                        @click="addToCart(dish)"
                    >
                        Add
                    </button>
                </li>

                <li v-if="filteredDishes.length === 0" class="px-4 py-6 text-center text-sm text-gray-500">
                    No products match your search.
                </li>
            </ul>
        </div>

        <div class="space-y-3 rounded-lg border border-gray-200 p-4">
            <h3 class="font-bold text-gray-900">Order</h3>

            <p v-if="cartLines.length === 0" class="text-sm text-gray-500">No products added yet.</p>

            <ul v-else class="space-y-3">
                <li v-for="line in cartLines" :key="line.dishId" class="space-y-1 text-sm">
                    <div class="flex items-center justify-between gap-2">
                        <span class="flex-1">{{ line.dish.name }}</span>
                        <input
                            type="number"
                            min="1"
                            class="w-16 rounded border-gray-300 shadow-sm focus:border-gray-500 focus:ring-gray-500"
                            :value="line.quantity"
                            @change="setQuantity(line.dishId, $event.target.value)"
                        >
                        <span class="w-16 text-right">&euro; {{ formatPrice(line.lineTotal) }}</span>
                        <button type="button" class="text-red-600 hover:underline" @click="removeFromCart(line.dishId)">
                            &times;
                        </button>
                    </div>
                    <input
                        type="text"
                        maxlength="255"
                        placeholder="Note (e.g. no onions)"
                        class="w-full rounded border-gray-300 text-xs shadow-sm focus:border-gray-500 focus:ring-gray-500"
                        :value="notes.get(line.dishId) ?? ''"
                        @input="setNote(line.dishId, $event.target.value)"
                    >
                </li>
            </ul>

            <div class="flex justify-between border-t border-gray-200 pt-3 font-bold text-gray-900">
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
                class="w-full rounded bg-gray-900 px-4 py-2 text-sm font-medium text-white hover:bg-gray-700 disabled:cursor-not-allowed disabled:opacity-50"
                :disabled="cartLines.length === 0"
            >
                Confirm order
            </button>
        </div>
    </div>
</template>
