<script>
  import { base } from "$app/paths";
  import Question from '$lib/question.svelte'
  import { fade, fly } from 'svelte/transition'
  import { store } from './store.js'
  import Results from './Results.svelte';
  $: scoreAnsiedade = $store.scoresByCategory && $store.scoresByCategory["Ansiedade"] || 0;
  // $: console.log(store.categoriesArray);
 

  const questions = [
    {
      id: 1,
      type: "multiple",
      difficulty: "easy",
      category: "Ansiedade",
      question: "Com que frequência você se sente nervoso(a) ou ansioso(a) antes de provas ou apresentações?",
      answers: [
        {id: 1, text: "Não me sinto ansioso", score: 0, image:"https://i.pinimg.com/originals/1f/c9/59/1fc959945e3daa2ad87a41c1f520a7fa.jpg" },
        {id: 2, text: "Pouco ansioso", score: 10, image: "" },
        {id: 3, text: "Muito ansioso", score: 20, image: "" },
        {id: 4, text: "Ansiedade enorme", score: 30, image: "" }
      ]
    },
    {
      id: 2,
      type: "multiple",
      difficulty: "easy",
      category: "Ansiedade",
      question: "Com que frequência você se sente nervoso(a) ou ansioso(a) antes de provas ou apresentações?2",
      answers: [
        {id: 1, text: "Não me sinto ansioso", score: 0, image: "" },
        {id: 2, text: "Pouco ansioso", score: 10, image: "" },
        {id: 3, text: "Muito ansioso", score: 20, image: "" },
        {id: 4, text: "Ansiedade enorme", score: 30, image: "" }
      ]
    }
    // Adicione mais perguntas aqui...
  ];


  let activeQuestion = 0;
  let showResults = false;

  let isAnswered = false;
  
  // Atualize a função nextQuestion para resetar o estado
  function nextQuestion() {
    isAnswered = false;
    if (activeQuestion < questions.length - 1) {
      activeQuestion = activeQuestion + 1;
      store.next();
    } else {
      showResults = true;
    }
  }

  function resetQuiz() {
    showResults = false;
    store.reset();
    activeQuestion = 0;
  }

  $: questionNumber = activeQuestion + 1;
</script>


{#if showResults}
<div in:fade>
  <Results {scoreAnsiedade} {resetQuiz} />
</div>
{:else}
<div class="quiz-container">
  <header class="quiz-header">
    <div class="header-content">
      <h1 class="quiz-title">Pergunta <span class="question-number">#{questionNumber}</span></h1>
      <div class="quiz-controls">
        <button class="reset-btn" on:click={resetQuiz}>
          <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M3 12a9 9 0 1 0 9-9 9.75 9.75 0 0 0-6.74 2.74L3 8"></path>
            <path d="M3 3v5h5"></path>
          </svg>
          Reiniciar
        </button>
        
        {#if isAnswered}
          <button class="next-btn" on:click={nextQuestion}>
            {activeQuestion < questions.length - 1 ? 'Próxima' : 'Finalizar'}
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <path d="M5 12h14M12 5l7 7-7 7"/>
            </svg>
          </button>
        {/if}
      </div>
    </div>
  </header>

  <main class="quiz-content">
    {#each questions as question, index}
      {#if index === activeQuestion}
        <div out:fade in:fly={{ y: 200, duration: 1000 }} class="fade-wrapper">
          <Question 
            {question} 
            bind:isAnswered
            onAnswerSelected={() => isAnswered = true}
            {nextQuestion} 
          />  
        </div>
      {/if}
    {/each}
  </main>


  <footer class="quiz-footer">
    <!-- Barra de progresso -->
    <div class="progress-container">
      <div class="progress-bar" style="width: {(activeQuestion / questions.length) * 100}%"></div>
      <div class="progress-steps">
        {#each Array(questions.length) as _, i}
        <button 
          class="progress-step {i === activeQuestion ? 'active' : ''} {i < activeQuestion ? 'completed' : ''}"
          on:click={() => activeQuestion = i}
          on:keydown={(e) => e.key === 'Enter' || e.key === ' ' ? activeQuestion = i : null}
          role="tab"
          aria-label={`Ir para pergunta ${i + 1}`}
          tabindex="0"
        ></button>
      {/each}
      </div>
    </div>
    
    <div class="footer-actions">
      <a href="{base}/" class="home-link" on:click={resetQuiz}>
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
          <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
          <polyline points="9 22 9 12 15 12 15 22"></polyline>
        </svg>
        Voltar ao Início
      </a>
      <div class="progress-text">Pergunta {questionNumber} de {questions.length}</div>
    </div>
  </footer>
</div>
<style>
  /* Adicione esses estilos */
  .quiz-controls {
    display: flex;
    gap: 10px;
    align-items: center;
  }
  
  .next-btn {
    background: #3E7BFF;
    color: white;
    padding: 8px 16px;
    border-radius: 20px;
    border: none;
    display: flex;
    align-items: center;
    gap: 5px;
    cursor: pointer;
    transition: background 0.3s;
  }
  
  .next-btn:hover {
    background: #3E6BFF;
  }
  
  .next-btn svg {
    margin-left: 5px;
  }
</style>
{/if}


<style>
  :global(body) {
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    background-color: #f8f9fa;
    margin: 0;
    padding: 0;
    color: #333;
    min-height: 100vh;
  }
  .progress-container {
    width: 100%;
    margin-bottom: 1.5rem;
    position: relative;
  }

  .progress-bar {
    height: 6px;
    background-color: #3E7BFF;
    border-radius: 3px;
    transition: width 0.3s ease;
  }

  .progress-steps {
    display: flex;
    justify-content: space-between;
    position: absolute;
    top: -6px;
    width: 100%;
  }

  .progress-step {
    width: 18px;
    height: 18px;
    border-radius: 50%;
    background-color: #e0e0e0;
    border: none; 
  background: none; 
  padding: 0; 
  cursor: pointer; 
    transition: all 0.3s ease;
    position: relative;
    z-index: 2;
  }

  .progress-step.completed {
    background-color: #3E7BFF;
  }

  .progress-step.active {
    background-color: white;
    border: 3px solid #3E7BFF;
    transform: scale(1.2);
  }

  .footer-actions {
    display: flex;
    justify-content: space-between;
    align-items: center;
    width: 100%;
  }

  .progress-text {
    color: #777;
    font-size: 0.9rem;
    font-weight: 500;
  }

  @media (max-width: 768px) {
    .progress-step {
      width: 14px;
      height: 14px;
    }
    
    .progress-bar {
      height: 4px;
    }
  }
  .quiz-container {
    width: 100%;
    min-height: 100vh;
    display: flex;
    flex-direction: column;
    background-color: white;
  }

  .quiz-header {
    border-bottom: 1px solid #eee;
    padding: 1.5rem 2rem;
    background: white;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
    position: sticky;
    top: 0;
    z-index: 10;
  }

  .header-content {
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 1rem;
    max-width: 1200px;
    margin: 0 auto;
    width: 100%;
  }

  .quiz-title {
    font-size: 2rem;
    color: #2c3e50;
    margin: 0;
    font-weight: 700;
  }

  .question-number {
    color: #3E7BFF;
  }

  .quiz-controls {
    display: flex;
    align-items: center;
    gap: 1.5rem;
  }

  .reset-btn {
    background: #f5f5f5;
    color: #555;
    border: none;
    padding: 0.6rem 1.2rem;
    border-radius: 50px;
    cursor: pointer;
    display: flex;
    align-items: center;
    gap: 0.5rem;
    transition: all 0.3s ease;
    font-weight: 500;
    font-size: 0.95rem;
  }

  .reset-btn:hover {
    background: #e0e0e0;
    transform: translateY(-2px);
    box-shadow: 0 2px 5px rgba(0,0,0,0.1);
  }


  /* .score-badge {
    background: #3E7BFF;
    color: white;
    padding: 0.6rem 1.2rem;
    border-radius: 50px;
    font-weight: 600;
    font-size: 0.95rem;
  } */


  .quiz-content {
    flex: 1;
    padding: 2rem;
    max-width: 1200px;
    width: 100%;
    margin: 0 auto;
    box-sizing: border-box;
  }

  .fade-wrapper {
    position: relative;
    transition: all 300ms;
    width: 100%;
  }

  .quiz-footer {
    border-top: 1px solid #eee;
    padding: 1.5rem 2rem;
    background: white;
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 1rem;
  }

  .home-link {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    color: #555;
    text-decoration: none;
    font-weight: 500;
    transition: color 0.3s ease;
    padding: 0.5rem 1rem;
    border-radius: 5px;
  }

  .home-link:hover {
    color: #3E7BFF;
    background: #f5f5f5;
  }

  /* Estilo para telas grandes */
  @media (min-width: 1200px) {
    .quiz-content {
      padding: 3rem;
    }
  }

  @media (max-width: 768px) {
    .quiz-header, .quiz-footer {
      padding: 1.2rem;
    }
    
    .quiz-title {
      font-size: 1.6rem;
    }
    
    .header-content {
      flex-direction: column;
      align-items: stretch;
      gap: 1.2rem;
    }
    
    .quiz-controls {
      justify-content: space-between;
    }

    .quiz-content {
      padding: 1.5rem;
    }

    .reset-btn {
      padding: 0.5rem 1rem;
      font-size: 0.9rem;
    }
  }

  @media (max-width: 480px) {
    .quiz-header, .quiz-footer {
      padding: 1rem;
    }

    .quiz-title {
      font-size: 1.4rem;
    }

    .quiz-content {
      padding: 1rem;
    }

    .quiz-footer {
      flex-direction: column;
      text-align: center;
    }
  }


  @keyframes modalEnter {
    from {
      opacity: 0;
      transform: translateY(50px) scale(0.95);
    }
    to {
      opacity: 1;
      transform: translateY(0) scale(1);
    }
  }

  /* .modal-content {
    padding: 2.5rem;
    text-align: center;
    position: relative;
  }

  .trophy-icon {
    width: 80px;
    height: 80px;
    margin-bottom: 1.5rem;
    color: #FFD700;
    stroke-width: 1.5;
  }

  .modal-content h2 {
    font-size: 1.8rem;
    color: #2c3e50;
    margin-bottom: 1rem;
  }

  .modal-actions {
    display: flex;
    gap: 1rem;
    justify-content: center;
    flex-wrap: wrap;
  }

  .restart-btn {
    background: #3E7BFF;
    color: white;
    border: none;
    padding: 0.8rem 1.5rem;
    border-radius: 50px;
    cursor: pointer;
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-weight: 500;
    transition: all 0.3s ease;
  }

  .restart-btn:hover {
    background: #3e8e41;
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
  }

  .home-btn {
    background: #f5f5f5;
    color: #555;
    border: none;
    padding: 0.8rem 1.5rem;
    border-radius: 50px;
    cursor: pointer;
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-weight: 500;
    transition: all 0.3s ease;
    text-decoration: none;
  }

  .home-btn:hover {
    background: #e0e0e0;
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
  }

  .confetti {
    position: absolute;
    width: 10px;
    height: 10px;
    background-color: #f00;
    opacity: 0;
  }

  .confetti:nth-child(1) {
    background-color: #3E7BFF;
    top: 10%;
    left: 20%;
    animation: confetti 3s ease 0.5s infinite;
  }

  .confetti:nth-child(2) {
    background-color: #FF9800;
    top: 15%;
    left: 50%;
    animation: confetti 3s ease 1s infinite;
  }

  .confetti:nth-child(3) {
    background-color: #2196F3;
    top: 20%;
    left: 80%;
    animation: confetti 3s ease 1.5s infinite;
  }

  .confetti:nth-child(4) {
    background-color: #9C27B0;
    top: 25%;
    left: 40%;
    animation: confetti 3s ease 0.8s infinite;
  }

  .confetti:nth-child(5) {
    background-color: #FFD700;
    top: 30%;
    left: 60%;
    animation: confetti 3s ease 1.2s infinite;
  } */

  @keyframes confetti {
    0% {
      transform: translateY(0) rotate(0deg);
      opacity: 1;
    }
    100% {
      transform: translateY(100px) rotate(360deg);
      opacity: 0;
    }
  }

  /* @media (max-width: 600px) {
    .modal-content {
      padding: 1.5rem;
    }
    
    .modal-content h2 {
      font-size: 1.5rem;
    }
    
    .modal-actions {
      flex-direction: column;
      gap: 0.8rem;
    }
    
    .restart-btn, .home-btn {
      width: 100%;
      justify-content: center;
    }
  } */
</style>