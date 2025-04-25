import { writable } from "svelte/store";

export function persistStore(key, initial) {
    if (typeof window === "undefined") {
        return writable(initial);
    }

    const persist = localStorage.getItem(key);
    const store = writable(persist ? JSON.parse(persist) : initial);

    store.subscribe((value) => {
        if (typeof window !== "undefined") {
            localStorage.setItem(key, JSON.stringify(value));
        }
    });

    return store;
}