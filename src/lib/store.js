import { writable, get } from 'svelte/store'
import { persistStore } from './localDatabase'

const defaultData = {
  assign: Date.now(),
  scoresByCategory: {},
  current: 1,
  answered: false,
  answer: null,
  r: [],
  questionAnswers: {},
}

function createStore() {
    const dataStore = persistStore('data', writable(defaultData));
    const { subscribe, set, update } = dataStore;


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

    getAssign: () => {
      const $store = get(dataStore)
      return $store.assign
    },
    
    getCategoriesArray: () => {
      const $store = get(dataStore)
      const scores = $store.scoresByCategory || {}
      return Object.entries(scores).map(([name, score]) => ({
        name,
        score,
      }))
    },

    get categoriesArray() {
      const $store = get(dataStore)
      const scores = $store.scoresByCategory || {}
      return Object.entries(scores).map(([name, score]) => ({
        name,
        score,
      }))
    },

    getQuestionAnswer: questionId => {
      const $store = get(dataStore)
      return $store.questionAnswers?.[questionId] || null
    },

    save: (category, score, questionId, answerId) => {
      update(s => {
        const currentScores = s.scoresByCategory || {}
        const currentScore = currentScores[category] || 0

        return {
          ...s,
          scoresByCategory: {
            ...currentScores,
            [category]: currentScore + score,
          },
          questionAnswers: {
            ...s.questionAnswers,
            [questionId]: { answerId, score: Math.abs(score) },
          },
        }
      })
    },

    next: () => {
      update(s => ({
        ...s,
        current: s.current + 1,
        answered: false,
        answer: null,
      }))
    },

    reset: () => set(defaultData),

    getCategoryScore: category => {
      const $store = get(dataStore)
      return $store.scoresByCategory?.[category] || 0
    },

    setQuestions: questions => {
      update(s => ({ ...s, questions }))
    },

    // Obtendo as perguntas do store
    getQuestions: () => {
      const $store = get(dataStore)
      return $store.questions || []
    },

    submitToAPI: async (endpoint, extra = {}) => {
      const $store = get(dataStore)

      const respostasDetalhadas = Object.entries(
        $store.questionAnswers
      ).map(([questionId, resposta]) => {
        const pergunta = $store.questions.find(
          q => q.id === parseInt(questionId)
        )
        const alternativa = pergunta?.answers.find(
          a => a.id === resposta.answerId
        )

        return {
          id: questionId,
          pergunta: pergunta?.question || '',
          resposta: alternativa?.text || '',
          score: resposta.score,
        }
      })

      const payload = {
        assign: $store.assign,
        respostas: respostasDetalhadas,
        pontuacoes: $store.scoresByCategory,
        ...extra,
      }
     
      
      try {
        const response = await fetch(endpoint, {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
          },
          body: JSON.stringify(payload),
        })

        if (!response.ok) {
          throw new Error(`Erro: ${response.statusText}`)
        }

        return await response.json()
      } catch (err) {
        console.error('Falha ao enviar para API:', err)
        return null
      }
    },
  }
}

export const store = createStore()
