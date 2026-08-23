<script setup>
import { computed, ref } from 'vue';
import { readFavoriteIds, writeFavoriteIds } from './favorites';

const props = defineProps({
    dishKinds: {
        type: Array,
        default: () => [],
    },
    translations: {
        type: Object,
        default: () => ({}),
    },
    locale: {
        type: String,
        default: 'nl',
    },
});

const INTL_LOCALES = { nl: 'nl-NL', en: 'en-GB' };

function trans(key, replacements = {}) {
    let text = props.translations[key] ?? key;

    for (const [placeholder, value] of Object.entries(replacements)) {
        text = text.replace(`%${placeholder}%`, value);
    }

    return text;
}

const favoriteIds = ref(readFavoriteIds());
const showFavoritesOnly = ref(false);

function isFavorite(dishId) {
    return favoriteIds.value.includes(dishId);
}

function toggleFavorite(dishId) {
    favoriteIds.value = isFavorite(dishId)
        ? favoriteIds.value.filter((id) => id !== dishId)
        : [...favoriteIds.value, dishId];

    writeFavoriteIds(favoriteIds.value);
}

const visibleDishKinds = computed(() => {
    if (!showFavoritesOnly.value) {
        return props.dishKinds;
    }

    return props.dishKinds
        .map((dishKind) => ({
            ...dishKind,
            dishes: dishKind.dishes.filter((dish) => isFavorite(dish.id)),
        }))
        .filter((dishKind) => dishKind.dishes.length > 0);
});

function formatPrice(price) {
    const intlLocale = INTL_LOCALES[props.locale] ?? INTL_LOCALES.nl;

    return new Intl.NumberFormat(intlLocale, { minimumFractionDigits: 2, maximumFractionDigits: 2 }).format(price);
}
</script>

<template>
    <div>
        <div class="flex flex-wrap items-center justify-between gap-3 border-b border-black px-4 py-3">
            <p class="font-bold">
                <span aria-hidden="true">&#9733;</span>
                {{ trans('favoritesSaved', { count: favoriteIds.length }) }}
            </p>

            <button
                type="button"
                class="border border-black px-3 py-1 font-bold disabled:cursor-not-allowed disabled:opacity-50"
                :disabled="favoriteIds.length === 0"
                @click="showFavoritesOnly = !showFavoritesOnly"
            >
                {{ showFavoritesOnly ? trans('showFullMenu') : trans('showFavoritesOnly') }}
            </button>
        </div>

        <p v-if="showFavoritesOnly && visibleDishKinds.length === 0" class="px-4 py-6 text-center">
            {{ trans('noFavorites') }}
        </p>

        <div v-for="dishKind in visibleDishKinds" :key="dishKind.id" class="px-4 py-5">
            <h2 class="text-2xl underline pb-3">{{ dishKind.name }}</h2>

            <ul>
                <li
                    v-for="dish in dishKind.dishes"
                    :key="dish.id"
                    class="flex items-start justify-between gap-4 border-b border-dashed border-black py-2 last:border-b-0"
                >
                    <div>
                        <p class="font-bold">
                            <span class="pr-2">{{ dish.menuNumber }}.</span>{{ dish.name }}
                        </p>
                        <p v-if="dish.description" class="text-sm">{{ dish.description }}</p>
                    </div>

                    <div class="flex shrink-0 items-center gap-3">
                        <div class="text-right">
                            <p v-if="dish.discountedPrice !== null" class="text-xs font-bold uppercase text-red-600">
                                {{ trans('specialOffer') }}
                            </p>
                            <p v-if="dish.discountedPrice !== null" class="text-xs text-gray-400 line-through">
                                &euro; {{ formatPrice(dish.price) }}
                            </p>
                            <p class="font-bold whitespace-nowrap" :class="{ 'text-red-600': dish.discountedPrice !== null }">
                                &euro; {{ formatPrice(dish.discountedPrice ?? dish.price) }}
                            </p>
                        </div>

                        <button
                            type="button"
                            class="text-2xl leading-none"
                            :class="isFavorite(dish.id) ? 'text-yellow-500' : 'text-gray-300'"
                            :aria-pressed="isFavorite(dish.id)"
                            :aria-label="isFavorite(dish.id) ? trans('removeFavorite', { name: dish.name }) : trans('addFavorite', { name: dish.name })"
                            @click="toggleFavorite(dish.id)"
                        >
                            &#9733;
                        </button>
                    </div>
                </li>
            </ul>
        </div>
    </div>
</template>
