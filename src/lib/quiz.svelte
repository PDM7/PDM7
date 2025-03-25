<script>
  import Modal from '$lib/modal.svelte'
  import Question from '$lib/question.svelte'
  import { fade, fly } from 'svelte/transition'
  import { score } from './store.js'

  const questions = [
  {
    type: "multiple",
    difficulty: "easy",
    category: "Health: Mental Health",
    question: "O que é a ansiedade?",
    correct_answer: "Uma resposta natural do corpo ao estresse.",
    incorrect_answers: ["Uma doença mental incurável", "Uma reação apenas física", "Uma sensação de prazer constante"]
  },
  {
    type: "multiple",
    difficulty: "medium",
    category: "Health: Mental Health",
    question: "Qual é um dos sintomas comuns da ansiedade?",
    correct_answer: "Preocupação excessiva",
    incorrect_answers: ["Felicidade constante", "Falta de apetite", "Sonolência excessiva"]
  },
  {
    type: "multiple",
    difficulty: "hard",
    category: "Health: Mental Health",
    question: "Qual desses transtornos de ansiedade é caracterizado por ataques de pânico repentinos?",
    correct_answer: "Transtorno de pânico",
    incorrect_answers: ["Transtorno de ansiedade generalizada", "Fobia social", "Transtorno obsessivo-compulsivo"]
  },
  {
    type: "multiple",
    difficulty: "medium",
    category: "Health: Mental Health",
    question: "Qual é uma das formas mais comuns de tratamento para ansiedade?",
    correct_answer: "Terapia cognitivo-comportamental",
    incorrect_answers: ["Uso de antidepressivos", "Terapia ocupacional", "Meditação constante"]
  },
  {
    type: "multiple",
    difficulty: "easy",
    category: "Health: Mental Health",
    question: "A ansiedade pode afetar qual parte do corpo?",
    correct_answer: "Sistema nervoso",
    incorrect_answers: ["Sistema digestivo", "Sistema muscular", "Sistema respiratório"]
  },
  {
    type: "multiple",
    difficulty: "medium",
    category: "Health: Mental Health",
    question: "Quais são as principais causas da ansiedade?",
    correct_answer: "Fatores genéticos, ambientais e psicológicos",
    incorrect_answers: ["Fatores exclusivamente genéticos", "Fatores exclusivamente psicológicos", "Fatores alimentares"]
  },
  {
    type: "multiple",
    difficulty: "medium",
    category: "Health: Mental Health",
    question: "Qual dessas estratégias pode ajudar a controlar a ansiedade?",
    correct_answer: "Exercícios de respiração profunda",
    incorrect_answers: ["Evitar a socialização", "Comer alimentos processados", "Exercícios físicos intensos"]
  },
  {
    type: "multiple",
    difficulty: "hard",
    category: "Health: Mental Health",
    question: "O transtorno de ansiedade generalizada pode ser caracterizado por?",
    correct_answer: "Preocupações excessivas com diversas situações cotidianas",
    incorrect_answers: ["Medos específicos", "Ataques de pânico repentinos", "Fobias de lugares públicos"]
  },
  {
    type: "multiple",
    difficulty: "easy",
    category: "Health: Mental Health",
    question: "A ansiedade é sempre considerada um transtorno mental?",
    correct_answer: "Não, ela pode ser uma resposta normal ao estresse.",
    incorrect_answers: ["Sim, sempre é um transtorno", "Sim, é uma condição permanente", "Não, é sempre um sintoma físico"]
  },
  {
    type: "multiple",
    difficulty: "hard",
    category: "Health: Mental Health",
    question: "Qual dos seguintes pode ser um fator de risco para o transtorno de ansiedade social?",
    correct_answer: "Experiências traumáticas na infância",
    incorrect_answers: ["Excesso de atividade física", "Alimentação desequilibrada", "Exposição à luz solar"]
  }
];


  let activeQuestion = 0
  let isModalOpen = false

  function nextQuestion() {
    if (activeQuestion < questions.length - 1) {
      activeQuestion = activeQuestion + 1
    } else {
      isModalOpen = true
    }
  }

  function resetQuiz() {
    isModalOpen = false
    score.set(0)
    activeQuestion = 0
  }

  $: if ($score > 4) {
    isModalOpen = true
  }

  $: questionNumber = activeQuestion + 1
</script>

<div class="content"><h1 class="title">Pergunta #{questionNumber}</h1>
  <button on:click={resetQuiz}>Reiniciar quiz</button>
  <h3>Pontuação: {$score}</h3>
  <h4>Pergunta #{questionNumber} de {questions.length}</h4>

  {#each questions as question, index}
    {#if index === activeQuestion}
      <div
        out:fade
        in:fly={{ y: 200, duration: 1000 }}
        class="fade-wrapper"
      >
        <Question {question} {nextQuestion} />
      </div>
    {/if}
  {/each}
</div>

{#if isModalOpen}
  <Modal on:close={resetQuiz}>
    <h2>Parabéns 🎉</h2>
    <p>Obrigado pelas suas respostas!</p>
    <button on:click={resetQuiz}>Responder novamente</button>
  </Modal>
{/if}

<style>
  .fade-wrapper {
    position: relative;
    transition: all 300ms;
  }
</style>