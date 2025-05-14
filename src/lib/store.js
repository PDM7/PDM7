import { writable, get } from 'svelte/store'
import { persistStore } from './localDatabase'

const defaultData = {
  scoresByCategory: {},
  current: 1,
  answered: false,
  answer: null,
  r: [],
  questionAnswers: {},
}

function createStore() {
  const dataStore = persistStore('data', writable(defaultData))
  const { subscribe, set, update } = dataStore

  return {
    subscribe,

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
