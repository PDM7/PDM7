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
    answers: [
      {
        text: "Uma resposta natural do corpo ao estresse.",
        correct: true,
        image: "https://images.unsplash.com/photo-1591382386627-349b692688ff?w=500"
      },
      {
        text: "Uma doença mental incurável",
        correct: false,
        image: "https://images.unsplash.com/photo-1573497019940-1c28c88b4f3e?w=500"
      },
      {
        text: "Uma reação apenas física",
        correct: false,
        image: "https://images.unsplash.com/photo-1530026186672-2cd00ffc50fe?w=500"
      },
      {
        text: "Uma sensação de prazer constante",
        correct: false,
        image: "https://images.unsplash.com/photo-1518457900604-d0c5a27ee3a2?w=500"
      }
    ]
  },
  {
    type: "multiple",
    difficulty: "medium",
    category: "Health: Mental Health",
    question: "Qual é um dos sintomas comuns da ansiedade?",
    answers: [
      {
        text: "Preocupação excessiva",
        correct: true,
        image: "https://images.unsplash.com/photo-1573496558710-9453b0ca8d1b?w=500"
      },
      {
        text: "Felicidade constante",
        correct: false,
        image: "https://images.unsplash.com/photo-1518687075829-7b35f8ccf24e?w=500"
      },
      {
        text: "Falta de apetite",
        correct: false,
        image: "https://images.unsplash.com/photo-1567440208-59d5dbf06a61?w=500"
      },
      {
        text: "Sonolência excessiva",
        correct: false,
        image: "https://images.unsplash.com/photo-1557518344-8f45301302b5?w=500"
      }
    ]
  },
  
  {
    type: "multiple",
    difficulty: "hard",
    category: "Health: Mental Health",
    question: "Qual desses transtornos de ansiedade é caracterizado por ataques de pânico repentinos?",
    answers: [
      {
        text: "Transtorno de pânico",
        correct: true,
        image: "https://images.unsplash.com/photo-1557488380-cae6fe40526f?w=500"
      },
      {
        text: "Transtorno de ansiedade generalizada",
        correct: false,
        image: "https://images.unsplash.com/photo-1532571101-d03251b81360?w=500"
      },
      {
        text: "Fobia social",
        correct: false,
        image: "https://images.unsplash.com/photo-1562194133-b1116ca62cc7?w=500"
      },
      {
        text: "Transtorno obsessivo-compulsivo",
        correct: false,
        image: "https://images.unsplash.com/photo-1584798223149-09113a7136b1?w=500"
      }
    ]
  },
  {
    type: "multiple",
    difficulty: "medium",
    category: "Health: Mental Health",
    question: "Qual é uma das formas mais comuns de tratamento para ansiedade?",
    answers: [
      {
        text: "Terapia cognitivo-comportamental",
        correct: true,
        image: "https://images.unsplash.com/photo-1561481477-7e5de20a5771?w=500"
      },
      {
        text: "Uso de antidepressivos",
        correct: false,
        image: "https://images.unsplash.com/photo-1542092286-602f7c08b413?w=500"
      },
      {
        text: "Terapia ocupacional",
        correct: false,
        image: "https://images.unsplash.com/photo-1537670327890-441478f30219?w=500"
      },
      {
        text: "Meditação constante",
        correct: false,
        image: "https://images.unsplash.com/photo-1509021436668-e68270288f7a?w=500"
      }
    ]
  },
  {
    type: "multiple",
    difficulty: "easy",
    category: "Health: Mental Health",
    question: "A ansiedade pode afetar qual parte do corpo?",
    answers: [
      {
        text: "Sistema nervoso",
        correct: true,
        image: "https://images.unsplash.com/photo-1588921851294-c82fdb556118?w=500"
      },
      {
        text: "Sistema digestivo",
        correct: false,
        image: "https://images.unsplash.com/photo-1587742381858-b02c98442b04?w=500"
      },
      {
        text: "Sistema muscular",
        correct: false,
        image: "https://images.unsplash.com/photo-1562620915-58b8c436e34e?w=500"
      },
      {
        text: "Sistema respiratório",
        correct: false,
        image: "https://images.unsplash.com/photo-1532596309817-d1c593f647c9?w=500"
      }
    ]
  }
  // Continue para outras perguntas, conforme necessário
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


  $: questionNumber = activeQuestion + 1
</script>

{#if isModalOpen}
<Modal>
  <div class="modal-content">
    <div class="confetti"></div>
    <div class="confetti"></div>
    <div class="confetti"></div>
    <div class="confetti"></div>
    <div class="confetti"></div>
    
    <svg class="trophy-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
      <circle cx="12" cy="8" r="7"></circle>
      <polyline points="8.21 13.89 7 23 12 20 17 23 15.79 13.88"></polyline>
    </svg>
    
    <h2>Quiz Concluído! 🎉</h2>
    
    <div class="score-result">
      Você acertou <span class="highlight">{$score}</span> de <span>{questions.length}</span> perguntas!
    </div>
    
    {#if $score === questions.length}
      <p class="perfect-score">Perfeito! Você é um expert no assunto! 👏</p>
    {:else if $score >= questions.length/2}
      <p class="good-score">Bom trabalho! Continue aprendendo! 💪</p>
    {:else}
      <p class="improve-score">Você pode melhorar! Que tal tentar novamente? ✨</p>
    {/if}
    
    <div class="modal-actions">
      <button class="restart-btn" on:click={resetQuiz}>
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
          <path d="M3 12a9 9 0 1 0 9-9 9.75 9.75 0 0 0-6.74 2.74L3 8"></path>
          <path d="M3 3v5h5"></path>
        </svg>
        Jogar Novamente
      </button>
      <a href="/" class="home-btn" on:click={resetQuiz}>
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
          <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
          <polyline points="9 22 9 12 15 12 15 22"></polyline>
        </svg>
        Voltar ao Início
      </a>
    </div>
  </div>
</Modal>
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
        <div class="score-badge">Pontuação: <span>{$score}</span></div>
      </div>
    </div>
  </header>

  <main class="quiz-content">
    {#each questions as question, index}
      {#if index === activeQuestion}
        <div out:fade in:fly={{ y: 200, duration: 1000 }} class="fade-wrapper">
          <Question {question} {nextQuestion} />
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
          <div 
            class="progress-step {i === activeQuestion ? 'active' : ''} {i < activeQuestion ? 'completed' : ''}"
            on:click={() => activeQuestion = i}
          ></div>
        {/each}
      </div>
    </div>
    
    <div class="footer-actions">
      <a href="/" class="home-link" on:click={resetQuiz}>
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


  .score-badge {
    background: #3E7BFF;
    color: white;
    padding: 0.6rem 1.2rem;
    border-radius: 50px;
    font-weight: 600;
    font-size: 0.95rem;
  }

  .score-badge span {
    font-weight: 700;
    font-size: 1.1em;
  }

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

    .reset-btn, .score-badge {
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

 
  :global(.modal-overlay) {
    background: rgba(0, 0, 0, 0.7);
    backdrop-filter: blur(5px);
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 1000;
  }

  :global(.modal-container) {
    background: white;
    border-radius: 16px;
    max-width: 500px;
    width: 90%;
    padding: 0;
    overflow: hidden;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
    animation: modalEnter 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
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

  .modal-content {
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

  .score-result {
    font-size: 1.2rem;
    margin-bottom: 1.5rem;
    color: #555;
  }

  .highlight {
    font-size: 1.4rem;
    font-weight: 700;
    color: #3E7BFF;
  }

  .perfect-score {
    color: #FFD700;
    font-weight: 600;
    margin-bottom: 2rem;
  }

  .good-score {
    color: #3E7BFF;
    font-weight: 600;
    margin-bottom: 2rem;
  }

  .improve-score {
    color: #FF9800;
    font-weight: 600;
    margin-bottom: 2rem;
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
  }

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

  @media (max-width: 600px) {
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
  }
</style>