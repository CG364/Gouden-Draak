const COOKIE_NAME = 'gouden_draak_favorite_dishes';
const COOKIE_DAYS = 365;

export function readFavoriteIds() {
    const match = document.cookie.match(new RegExp(`(?:^|; )${COOKIE_NAME}=([^;]*)`));

    if (!match) {
        return [];
    }

    try {
        const ids = JSON.parse(decodeURIComponent(match[1]));

        return Array.isArray(ids) ? ids.filter((id) => Number.isInteger(id)) : [];
    } catch {
        return [];
    }
}

export function writeFavoriteIds(ids) {
    const expires = new Date();
    expires.setTime(expires.getTime() + COOKIE_DAYS * 24 * 60 * 60 * 1000);

    document.cookie = `${COOKIE_NAME}=${encodeURIComponent(JSON.stringify(ids))}; expires=${expires.toUTCString()}; path=/; samesite=lax`;
}
