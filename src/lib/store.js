import { writable } from 'svelte/store';
import { persistStore } from './localDatabase';

const defaultData = {
    scoresByCategory: {},
    current: 1,
    answered: false,
    answer: null,
    r: [],
    questionAnswers: {} 
};

function createStore() {
    const { subscribe, set, update } = persistStore('data', writable(defaultData));

    return {
        subscribe,
        
        getCategoriesArray: () => {
            let categories = [];
            subscribe(($store) => {
                // Verificação segura
                const scores = $store.scoresByCategory || {};
                categories = Object.entries(scores).map(([name, score]) => ({
                    name, 
                    score
                }));
            })();
            return categories;
        },
        
        get categoriesArray() {
            let arr = [];
            subscribe($store => {
                // Verificação segura
                const scores = $store.scoresByCategory || {};
                arr = Object.entries(scores).map(([name, score]) => ({
                    name,
                    score
                }));
            })();
            return arr;
        },
        getQuestionAnswer: (questionId) => {
            let answer;
            subscribe($store => {
                answer = $store.questionAnswers?.[questionId] || null;
            })();
            return answer;
        },
        
        // Modifique o método save:
        save: (category, score, questionId, answerId) => {
            update(s => {
                const currentScores = s.scoresByCategory || {};
                const currentScore = currentScores[category] || 0;
                
                // Encontra resposta anterior para esta pergunta
                const previousAnswer = s.questionAnswers?.[questionId];
                
                return {
                    ...s,
                    scoresByCategory: {
                        ...currentScores,
                        [category]: currentScore + score
                    },
                    questionAnswers: {
                        ...s.questionAnswers,
                        [questionId]: score !== 0 ? { answerId, score: Math.abs(score) } : null
                    }
                };
            });
        },

        next: () => {
            update(s => ({
                ...s,
                current: s.current + 1,
                answered: false,
                answer: null
            }));
        },

        reset: () => set(defaultData),
        
        getCategoryScore: (category) => {
            let score = 0;
            subscribe($store => {
                // Verificação segura
                const scores = $store.scoresByCategory || {};
                score = scores[category] || 0;
            })();
            return score;
        },
       
    };
}

export const store = createStore();