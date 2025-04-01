import { writable } from 'svelte/store';
import { persistStore } from './localDatabase';

const defaultData = {
    score: 0,
    current: 1,
    answered: false,
    answer: null,
    r: []
};

function createStore() {
    const { subscribe, set, update } = persistStore('data', writable(defaultData));

    return {
        subscribe,

        save: (answer, form_id, game_id, user_id, question_id, card_id, group_id, agent_id) => {
            update(s => ({
                ...s,
                score: s.score + 1,
                answered: true,
                answer,
                r: [
                    ...s.r,
                    { form_id, game_id, user_id, question_id, card_id, group_id, agent_id }
                ]
            }));
        },

        next: () => {
            update(s => ({
                ...s,
                current: s.score + 1,
                answered: false,
                answer: null
            }));
        },

        reset: () => set(defaultData)
    };
}

export const store = createStore();
