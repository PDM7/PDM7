<script>
  import { store } from './store.js'
  import { onMount } from 'svelte'
  import {base} from "$app/paths"

  export let question;
  export let nextQuestion;
  export let isAnswered;
 

  let selectedAnswer = null
 
  

  // Função para gerar cores e ícones dinamicamente
  function getDefaultStyle(index) {
    const colors = ['#EF5350', '#42A5F5', '#66BB6A', '#FFA726', '#AB47BC', '#26C6DA', '#D4E157', '#FF7043'];
    const icons = ['🔴', '🔵', '🟢', '🟠', '🟣', '🟡', '🟤', '⚪'];
    
    return {
      color: colors[index % colors.length],
      icon: icons[index % icons.length]
    };
  }

  // Verifica se a imagem existe
  async function checkImageExists(imageUrl, index, element) {
    if (!imageUrl || imageUrl === '') {
      applyDefaultStyle(index, element);
      return;
    }

    // try {
    //   const response = await fetch(imageUrl, { method: 'HEAD' });
    //   if (!response.ok) throw new Error('Image not found');
      
    //   // Verifica se o conteúdo é realmente uma imagem
    //   const contentType = response.headers.get('Content-Type');
    //   if (!contentType || !contentType.startsWith('image/')) {
    //     throw new Error('Not an image');
    //   }
    // } catch (error) {
    //   applyDefaultStyle(index, element);
    // }
  }

  function applyDefaultStyle(index, element) {
    const style = getDefaultStyle(index);
    const iconContainer = element.querySelector('.icon-container');
    if (iconContainer) {
      iconContainer.style.backgroundColor = style.color;
      iconContainer.querySelector('.default-icon').textContent = style.icon;
      iconContainer.querySelector('.default-icon').style.display = 'block';
      const img = iconContainer.querySelector('.answer-image');
      if (img) img.style.display = 'none';
    }
  }

  // Função para permitir mudar a resposta
  function handleAnswerClick(answer) {
    if (selectedAnswer?.id === answer.id) {
      selectedAnswer = null;
      isAnswered = false;
      store.save(question.category, -answer.score, question.id, answer.id); 
    } else {
      if (selectedAnswer) {
        store.save(question.category, -selectedAnswer.score, question.id, selectedAnswer.id);
      }
      selectedAnswer = answer;
      isAnswered = true;
      store.save(question.category, answer.score, question.id, answer.id);
    }
  }
  onMount(() => {
    // Verifica resposta salva
    const savedAnswer = store.getQuestionAnswer(question.id);
    if (savedAnswer) {
        isAnswered = true;
        selectedAnswer = question.answers.find(a => a.id === savedAnswer.answerId);
    }

    // Verifica imagens
    document.querySelectorAll('.answer-card').forEach((card, index) => {
        const img = card.querySelector('.answer-image');
        if (img && img.src) {
            checkImageExists(img.src, index, card);
        }
    });
});
</script>

<h3>
  {@html question.question}
</h3>

{#if isAnswered}
  <h4>Vamos pra próxima 🎉</h4>
{/if}

<div class="answers-grid">
  {#each question.answers as answer, index}
    <div class="answer-card">
      <button
        on:click={() => handleAnswerClick(answer)}
        class:selected-answer={selectedAnswer?.id === answer.id}
      >
        <div class="icon-container">
          <img 
          src={answer.image || "{base}/images/1.png"}
          alt={answer.text} 
          class="answer-image" 
          on:error={(e) => {
            e.target.src = "{base}/images/1.png";
            applyDefaultStyle(index, e.target.closest('.answer-card'));
          }}
        />
        

          <div class="default-icon"></div>
        </div>
        <div class="answer-text">{@html answer.text}</div>
        {#if selectedAnswer?.id === answer.id}
          <div class="answer-checkmark">✓</div>
        {/if}
      </button>
    </div>
  {/each}
</div>

{#if isAnswered}
  <div class="next-button">
    <button class="next-btn" on:click={nextQuestion}>Próxima pergunta</button>
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