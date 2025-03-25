<script>
    import Modal from '$lib/modal.svelte'
    import Question from '$lib/question.svelte'
    import { fade, fly } from 'svelte/transition'
    import { score } from './store.js'
  
    let questions = []
    let activeQuestion = 0
    let isModalOpen = false
    let isLoading = true
  
    async function getQuiz() {
      isLoading = true
      try {
        const res = await fetch(
          'https://opentdb.com/api.php?amount=10&category=11&type=multiple'
        )
        const quiz = await res.json()
        questions = quiz.results 
      } catch (error) {
        console.error('Error fetching quiz:', error)
      } finally {
        isLoading = false
      }
    }
  
    getQuiz()
  
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
      getQuiz()
      activeQuestion = 0
    }

    $: if ($score > 4) {
      isModalOpen = true
    }
  
    $: questionNumber = activeQuestion + 1
  </script>
  
  <div>
    <button on:click={resetQuiz}>Reiniciar quiz</button>
    <h3>Pontuação: {$score}</h3>
    <h4>Pergunta #{questionNumber} de {questions.length}</h4>
  
    {#if isLoading}
      <p>Carregando...</p>
    {:else if questions.length > 0}
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
    {:else}
      <p>Não foi possível carregar as perguntas.</p>
    {/if}
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