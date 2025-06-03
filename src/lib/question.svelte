<script>
  import { store } from './store.js'
  import { onMount, createEventDispatcher } from 'svelte'
  import { base } from "$app/paths"

  // Props received from Quiz component
  export let question;
  export let nextQuestion; // Function to call to advance
  export let isAnswered;   // Bound variable indicating if an answer is selected
  export let isLastQuestion; // Boolean indicating if this is the last question

  const dispatch = createEventDispatcher();

  let selectedAnswer = null;
 
  // Function to generate default styles if image fails
  function getDefaultStyle(index) {
    const colors = ['#EF5350', '#42A5F5', '#66BB6A', '#FFA726', '#AB47BC', '#26C6DA', '#D4E157', '#FF7043'];
    const icons = ['🔴', '🔵', '🟢', '🟠', '🟣', '🟡', '🟤', '⚪'];
    return {
      color: colors[index % colors.length],
      icon: icons[index % icons.length]
    };
  }

  // Apply default style to an element
  function applyDefaultStyle(index, element) {
    const style = getDefaultStyle(index);
    const iconContainer = element.querySelector('.icon-container');
    if (iconContainer) {
      iconContainer.style.backgroundColor = style.color;
      const defaultIcon = iconContainer.querySelector('.default-icon');
      if (defaultIcon) {
        defaultIcon.textContent = style.icon;
        defaultIcon.style.display = 'block';
      }
      const img = iconContainer.querySelector('.answer-image');
      if (img) img.style.display = 'none';
    }
  }

  // Handle image loading errors
  function handleImageError(event, index) {
    // Fallback to a default image or apply default style
    // event.target.src = `${base}/images/default_fallback.png`; // Optional: set a fallback image
    applyDefaultStyle(index, event.target.closest('.answer-card'));
  }

  // Handle clicking an answer
  function handleAnswerClick(answer) {
    let scoreChange = 0;
    let previousAnswerId = selectedAnswer?.id;

    if (selectedAnswer?.id === answer.id) {
      // Deselecting the current answer
      scoreChange = -selectedAnswer.score;
      selectedAnswer = null;
      isAnswered = false;
    } else {
      // Selecting a new answer (or the first answer)
      if (selectedAnswer) {
        // Subtract score of previously selected answer if changing selection
        scoreChange -= selectedAnswer.score;
      }
      selectedAnswer = answer;
      scoreChange += selectedAnswer.score;
      isAnswered = true;
    }

    // Update the store with the change
    store.save(question.category, scoreChange, question.id, answer.id, previousAnswerId);
    
    // Dispatch event to notify parent (Quiz component)
    dispatch('answerSelected'); 
  }

  // Lifecycle function: Runs when the component mounts
  onMount(() => {
    // Check if there's a saved answer for this question in the store
    const savedAnswerData = store.getQuestionAnswer(question.id);
    if (savedAnswerData) {
        isAnswered = true;
        selectedAnswer = question.answers.find(a => a.id === savedAnswerData.answerId);
    }

    // Initial image check (optional, error handler might be sufficient)
    // document.querySelectorAll('.answer-card').forEach((card, index) => {
    //     const img = card.querySelector('.answer-image');
    //     if (img && img.src && !img.complete) { // Check if image hasn't loaded yet
    //         // Could add more robust checking here if needed
    //     }
    // });
  });
</script>

<!-- Question Text -->
<h3 class="question-title">
  {@html question.question}
</h3>

<!-- Optional message indicating selection -->
{#if isAnswered}
  <h4 class="selection-feedback">Vamos pra próxima 🎉</h4>
{/if}

<!-- Grid of Answer Options -->
<div class="answers-grid">
  {#each question.answers as answer, index}
    <div class="answer-card">
      <button
        class="answer-button"
        class:selected-answer={selectedAnswer?.id === answer.id}
        on:click={() => handleAnswerClick(answer)}
        aria-pressed={selectedAnswer?.id === answer.id}
      >
        <div class="icon-container">
          <!-- Attempt to load answer image, fallback on error -->
       <img 
  src={answer.image && answer.image.trim() !== '' ? answer.image : `${base}/images/1.png`}
  alt={answer.text}
  class="answer-image"
  on:error={(e) => {
    if (e.target.src !== `${base}/images/1.png`) {
      e.target.src = `${base}/images/1.png`;
      applyDefaultStyle?.(index, e.target.closest('.answer-card'));
    }
  }}
/>


          <!-- Default icon shown if image fails -->
          <div class="default-icon" aria-hidden="true"></div>
        </div>
        <div class="answer-text">{@html answer.text}</div>
        <!-- Checkmark for selected answer -->
        {#if selectedAnswer?.id === answer.id}
          <div class="answer-checkmark" aria-hidden="true">✓</div>
        {/if}
      </button>
    </div>
  {/each}
</div>

<!-- Next Button (shown only when an answer is selected) -->
{#if isAnswered}
  <div class="next-button">
    <button on:click={nextQuestion}> <!-- Use the passed nextQuestion function -->
      {isLastQuestion ? 'Finalizar' : 'Próxima pergunta'} <!-- Change text based on isLastQuestion -->
    </button>
  </div>
{/if}

<style>
  .answer-card button {
  transition: all 0.2s ease;
}



.answer-card button:hover:not(.selected-answer) {
  transform: translateY(-2px);
  box-shadow: 0 2px 5px rgba(0,0,0,0.1);
}

.selected-answer {
  border: 2px solid #4CAF50 !important;
  background-color: #e8f5e9;
  transform: translateY(-2px);
  box-shadow: 0 4px 8px rgba(0,0,0,0.1);
}
  .selected-answer {
    border: 2px solid #4CAF50;
    background-color: #e8f5e9;
  }
  
  .answer-checkmark {
    position: absolute;
    top: -10px;
    right: -10px;
    background: #4CAF50;
    color: white;
    border-radius: 50%;
    width: 20px;
    height: 20px;
    display: flex;
    align-items: center;
    /* justify-content: center; */
    font-size: 12px;
  }
 h3 {
    color: rgb(54, 33, 79);
    margin-bottom: 20px;
  }

  h4 {
    margin: 15px 0;
  }

  .answers-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 15px;
    margin: 20px 0;
  }

  .answer-card {
    display: flex;
    height: 100%;
    vertical-align: center;
  }

  .answer-card button {
    display: flex;
    /* flex-direction: column; */
    height: 100%;
    width: 100%;
    padding: 0;
    border: none;
    border-radius: 8px;
    overflow: hidden;
    background: none;
    cursor: pointer;
    transition: transform 0.2s, box-shadow 0.2s;
  }

  .answer-card button:hover:not(:disabled) {
    transform: translateY(-3px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.2);
  }

  .answer-card button:disabled {
    cursor: default;
    opacity: 0.8;
  }

  .icon-container {
    position: relative;
    min-width: 164px;
    height: 150px;
    display: flex;
    vertical-align: center;
    background-color: transparent;
  }

  .answer-image {
    min-width: 164px;
    height: 100%;
    aspect-ratio: 1;
    object-fit: cover;
    border-radius: 6px;
  }

  .default-icon {
    position: absolute;
    font-size: 3em;
    display: none;
    vertical-align: center;
  }

  .answer-text {
    padding: 12px;
    background: #f5f5f5;
    text-align: center;
    vertical-align: center;
    width: 100%;
    justify-content: center;
    align-items: center;
    height: 100%;
    font-size: large;
  }

  .next-button {
    margin-top: 20px;
    text-align: center;
  }

  .next-button button { 
    background: #3E7BFF;
    color: white;
    padding: 8px 16px;
    border-radius: 20px;
    border: none;
    display: flex;
    margin-left: auto;
    align-items: center;
    gap: 5px;
    cursor: pointer;
    transition: background 0.3s;
    min-height: 40px;
  }
   
  .next-button button:hover {
    background: #3E6BFF;
  }

  @media (max-width: 600px) {
    .answers-grid {
      grid-template-columns: 1fr;
    }
  }
</style>
