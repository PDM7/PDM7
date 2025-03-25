<script>
  import { score } from './store.js'

  export let question
  export let nextQuestion

  let selectedAnswer = null
  let isAnswered = false

  // Não precisamos mais do shuffle pois as respostas já vêm prontas
  // com a estrutura { text, correct, image }

  function checkQuestion(answer) {
    isAnswered = true
    selectedAnswer = answer
    if (answer.correct) {
      score.update(currentValue => currentValue + 1)
    }
  }
</script>

<h3>
  {@html question.question}
</h3>

{#if isAnswered}
  <h4 class:correct={selectedAnswer.correct} class:incorrect={!selectedAnswer.correct}>
    {#if selectedAnswer.correct}
      Boa, vamos pra próxima 🎉
    {:else}
      Xiii, deu ruim 😬
    {/if}
  </h4>
{/if}

<div class="answers-grid">
  {#each question.answers as answer}
    <button
      disabled={isAnswered}
      on:click={() => checkQuestion(answer)}
      class:correct-answer={isAnswered && answer.correct}
      class:wrong-answer={isAnswered && !answer.correct && selectedAnswer === answer}
    >
      <img src={answer.image} alt={answer.text} class="answer-image" />
      <div class="answer-text">{@html answer.text}</div>
    </button>
  {/each}
</div>

{#if isAnswered}
  <div class="next-button">
    <button on:click={nextQuestion}>Próxima pergunta</button>
  </div>
{/if}

<style>
  h3 {
    color: rgb(54, 33, 79);
    margin-bottom: 20px;
  }

  h4 {
    margin: 15px 0;
  }

  h4.correct {
    color: #2e7d32;
  }

  h4.incorrect {
    color: #c62828;
  }

  .answers-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 15px;
    margin: 20px 0;
  }

  button {
    border: none;
    border-radius: 8px;
    overflow: hidden;
    padding: 0;
    background: none;
    cursor: pointer;
    transition: transform 0.2s, box-shadow 0.2s;
  }

  button:hover:not(:disabled) {
    transform: translateY(-3px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.2);
  }

  button:disabled {
    cursor: default;
    opacity: 0.8;
  }

  .answer-image {
    width: 100%;
    height: 150px;
    object-fit: cover;
  }

  .answer-text {
    padding: 12px;
    background: #f5f5f5;
  }

  .correct-answer {
    outline: 3px solid #2e7d32;
  }

  .wrong-answer {
    outline: 3px solid #c62828;
  }

  .next-button {
    margin-top: 20px;
    text-align: center;
  }

  .next-button button {
    background: #3f51b5;
    color: white;
    padding: 10px 20px;
    border-radius: 5px;
  }

  @media (max-width: 600px) {
    .answers-grid {
      grid-template-columns: 1fr;
    }
  }
</style>