<script>
  import { store } from './store.js'
  import { onMount } from 'svelte'

  export let question
  export let nextQuestion

  let selectedAnswer = null
  let isAnswered = false

  // Função para gerar cores e ícones dinamicamente
  function getDefaultStyle(index) {
    const colors = ['#EF5350', '#42A5F5', '#66BB6A', '#FFA726', '#AB47BC', '#26C6DA', '#D4E157', '#FF7043'];
    const icons = ['🔴', '🔵', '🟢', '🟠', '🟣', '🟡', '🟤', '⚪'];
    
    return {
      color: colors[index % colors.length],
      icon: icons[index % icons.length]
    };
  }

  function checkQuestion(answer) {
    isAnswered = true
    selectedAnswer = answer
    if (answer.correct) {
      store.save("A", 1, 0, 0, 1, 1, 1, 1)
    }
  }

  // Verifica se a imagem existe
  async function checkImageExists(imageUrl, index, element) {
    if (!imageUrl) {
      applyDefaultStyle(index, element);
      return;
    }

    //Vamos simplificar. (ricardodarocha)
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

  // Verifica as imagens quando o componente é montado
  onMount(() => {
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
  <h4 class:correct={selectedAnswer.correct} class:incorrect={!selectedAnswer.correct}>
      Vamos pra próxima 🎉
  </h4>
{/if}

<div class="answers-grid">
  {#each question.answers as answer, index}
    <div class="answer-card">
      <button
        disabled={isAnswered}
        on:click={() => checkQuestion(answer)}
        class:correct-answer={isAnswered && selectedAnswer === answer}
      >
        <div class="icon-container">
          <img 
            src={"https://i.pinimg.com/originals/1f/c9/59/1fc959945e3daa2ad87a41c1f520a7fa.jpg"}
            alt={answer.text} 
            class="answer-image" 
            on:error={(e) => applyDefaultStyle(index, e.target.closest('.answer-card'))}
          />
          <div class="default-icon"></div>
        </div>
        <div class="answer-text">{@html answer.text}</div>
      </button>
    </div>
  {/each}
</div>

{#if isAnswered}
  <div class="next-button">
    <button on:click={nextQuestion}>Próxima pergunta</button>
  </div>
{/if}

<style>
  /* (Mantemos os mesmos estilos da versão anterior) */
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

  .answer-card {
    display: flex;
    flex-direction: column;
    height: 100%;
  }

  .answer-card button {
    display: flex;
    flex-direction: column;
    height: 100%;
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
    width: 100%;
    height: 150px;
    display: flex;
    align-items: center;
    justify-content: center;
    background-color: transparent;
  }

  .answer-image {
    width: 100%;
    height: 100%;
    object-fit: cover;
  }

  .default-icon {
    position: absolute;
    font-size: 3em;
    display: none;
  }

  .answer-text {
    padding: 12px;
    background: #f5f5f5;
    text-align: center;
  }

  .correct-answer {
    outline: 3px solid #2e7d32;
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
    border: none;
    cursor: pointer;
  }

  @media (max-width: 600px) {
    .answers-grid {
      grid-template-columns: 1fr;
    }
  }
</style>