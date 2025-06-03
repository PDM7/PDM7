<script>
  export let score;
  export let category;
  export let resetQuiz;
  export let quizId; 
  import { onMount } from 'svelte';
  import { store } from './store.js'; 
  import { base } from "$app/paths";

  let showEmailInput = false;
  let email = '';
  
  
  function toggleEmailInput() {
    showEmailInput = !showEmailInput;
  }
  
  async function sendEmail() {
    if (!email) return;
        let id = quizId??1;
    try {
        // Pass email and potentially other relevant data from the store
        const response = await store.submitToAPI('https://pdm7.onrender.com/mail.php', { 
            id, email, category
        });

        if (response) {
            console.log("E-mail enviado com sucesso!", response);
            alert("Cópia das respostas enviada para o e-mail com sucesso!");
            showEmailInput = false;
            email = '';
        } else {
             console.log("E-mail API call completed, but response was not explicitly successful.");
             alert("Solicitação de envio de e-mail processada.");
             showEmailInput = false;
             email = '';
        }
    } catch (error) {
      console.error("Erro ao enviar e-mail:", error);
      alert("Ocorreu um erro ao enviar o e-mail. Por favor, tente novamente.");
    }
  }


  onMount(async () => {
    try {
        const querys = $store.querys || {}; 
        const answersData = $store.questionAnswers || {}; 
        const finalScore = score;
        const quizCategory = category;

        // Submit data to repository endpoint
        const bd = await store.submitToAPI('https://pdm7.onrender.com/repository.php?salvar', {
            qid: quizId ?? 1, 
            classe: querys.institution || 'N/A', 
            salvar: 'Master',
            score: finalScore,
            category: quizCategory,
            answers: answersData, 
            ...querys 
        });
        console.log("Data saved to repository:", bd);

 

    } catch (error) {
        console.error("Error in onMount data submission:", error);
        // Optionally notify user of submission error
    }
  });
</script>

<div class="results-page">
    {#each Array(20) as _, i}
        <div class="confetti" style="left: {Math.random() * 100}%; top: {Math.random() * 100}%; animation-delay: {Math.random() * 5}s; background-color: hsl({Math.random() * 360}, 100%, 50%);"></div>
    {/each}

  <svg class="trophy-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
      <circle cx="12" cy="8" r="7"></circle>
      <polyline points="8.21 13.89 7 23 12 20 17 23 15.79 13.88"></polyline>
  </svg>
  
  <h2>Envio finalizado! 🎉</h2>
  
  <div class="result-container">
      {#if category === 'Autoestima'}
          <div class="result-message result-generic">
              <h3 class="result-main-title">Obrigado por responder!</h3>
              <p>Lembre-se: a pessoa mais importante da sua vida é você. Cuide-se, respeite seus limites e celebre cada conquista. Este quiz foi só o começo. Siga se conhecendo e se valorizando, você merece se sentir bem todos os dias.</p>
          </div>
      {:else if category === 'Ansiedade'}
          {#if score <= 13}
              <div class="result-message result-good">
                  <h3 class="result-main-title">Ansiedade Leve</h3>
                  <p>A ansiedade leve é uma sensação natural de nervosismo ou preocupação que acontece em situações como provas, entrevistas ou mudanças importantes na vida. Ela pode causar um pouco de inquietação ou preocupação, mas não atrapalha as atividades diárias, inclusive é saudável e ajuda nos processos psíquicos.</p>
              </div>
          {:else if score <= 26}
              <div class="result-message result-warning">
                  <h3 class="result-main-title">Sinais de Alerta para Ansiedade</h3>
                  <p>Quando a ansiedade dura muito tempo ou começa a afetar suas atividades diárias é um sinal de que algo mais sério pode estar acontecendo. Sintomas como dificuldade para dormir, preocupação constante, tensão no corpo, alterações no apetite ou libido, baixa concentração ou medo excessivo são sinais de alerta.</p>
              </div>
          {:else}
              <div class="result-message result-alert">
                  <h3 class="result-main-title">Requer atenção para a ansiedade</h3>
                  <p>Se a ansiedade começar a atrapalhar sua rotina ou se os sintomas citados no sinal de alerta persistirem, é importante buscar a ajuda profissional. A psicoterapia e em alguns casos medicamentos, podem ajudar a controlar a ansiedade e melhorar sua qualidade de vida. Resistir a buscar ajuda, pode trazer sérios prejuízos. Cuidar da saúde mental não é fraqueza, é vida.</p>
              </div>
          {/if}
      {:else}
          <!-- Default display for other categories -->
           <div class="result-message result-generic">
              <h3 class="result-main-title">Quiz Concluído!</h3>
              <p>Obrigado por participar do quiz sobre {category}.</p>
              <p>Sua pontuação foi: <strong>{score}</strong>.</p>
          </div>
      {/if}
  </div>
  
  <div class="result-actions">
      <button class="restart-btn" on:click={resetQuiz}>
          <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <path d="M3 12a9 9 0 1 0 9-9 9.75 9.75 0 0 0-6.74 2.74L3 8"></path>
              <path d="M3 3v5h5"></path>
          </svg>
          Responder Novamente
      </button>
      <a href="{base}/" class="home-btn">
          <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
              <polyline points="9 22 9 12 15 12 15 22"></polyline>
          </svg>
          Voltar ao Início
      </a>
      <button class="email-btn" on:click={toggleEmailInput}>
          <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path>
              <polyline points="22,6 12,13 2,6"></polyline>
          </svg>
          Enviar por e-mail
      </button>
  </div>
  
  {#if showEmailInput}
    <div class="email-input-container">
      <input 
        type="email" 
        bind:value={email}
        placeholder="Digite seu e-mail"
        class="email-input"
        aria-label="Digite seu e-mail para receber os resultados"
      />
      <button on:click={sendEmail} class="send-email-btn" aria-label="Enviar resultados por e-mail">
          Enviar
      </button>
    </div>
  {/if}
</div>

<style>
  /* Styles remain the same as provided previously */
 .results-page {
      max-width: 800px;
      margin: 0 auto;
      padding: 2rem;
      text-align: center;
      position: relative;
      overflow: hidden;
  }

  .confetti {
      position: absolute;
      width: 8px;
      height: 8px;
      background-color: #f00;
      opacity: 0;
      animation: confetti 5s ease-out infinite;
      border-radius: 50%;
      z-index: 0;
  }

  @keyframes confetti {
      0% { transform: translateY(-10vh) rotate(0deg); opacity: 1; }
      100% { transform: translateY(110vh) rotate(720deg); opacity: 0; }
  }

  .trophy-icon {
      width: 60px; /* Slightly smaller */
      height: 60px;
      margin: 0 auto 1rem;
      display: block;
      color: #ffc107; /* Gold color */
  }

  .results-page h2 {
      color: #333;
      font-weight: 600;
      margin-bottom: 1.5rem;
  }

  .result-container {
      text-align: center;
      margin: 1.5rem auto;
      max-width: 600px;
  }

  .result-main-title {
      color: #2c3e50;
      font-size: 1.6rem; /* Adjusted size */
      margin-bottom: 1rem;
      text-align: center;
      font-weight: 600;
  }

  .result-message {
      padding: 1.5rem; /* Adjusted padding */
      border-radius: 8px;
      margin: 1rem 0;
      text-align: left;
      line-height: 1.6;
  }
  
  .result-message p {
      margin-bottom: 0.5rem;
  }
  
  .result-message p:last-child {
      margin-bottom: 0;
  }

  .result-generic {
      background-color: #f8f9fa;
      border-left: 5px solid #6c757d;
  }

  .result-good {
      background-color: #e8f5e9;
      border-left: 5px solid #4caf50;
  }

  .result-warning {
      background-color: #fff8e1;
      border-left: 5px solid #ffc107;
  }

  .result-alert {
      background-color: #ffebee;
      border-left: 5px solid #f44336;
  }

  .result-actions {
      display: flex;
      justify-content: center;
      gap: 1rem;
      margin-top: 2rem;
      flex-wrap: wrap;
  }

  .restart-btn, .home-btn, .email-btn, .send-email-btn {
      padding: 0.75rem 1.5rem;
      border-radius: 50px; /* Pill shape */
      display: inline-flex;
      align-items: center;
      gap: 0.5rem;
      text-decoration: none;
      cursor: pointer;
      transition: all 0.3s ease;
      font-weight: 500;
      border: none;
  }

  .restart-btn:hover, .home-btn:hover, .email-btn:hover, .send-email-btn:hover {
      transform: translateY(-2px);
      box-shadow: 0 4px 8px rgba(0,0,0,0.15);
  }

  .restart-btn {
      background-color: #3f51b5;
      color: white;
  }
  .restart-btn:hover {
      background-color: #303f9f;
  }

  .home-btn {
      background-color: #f5f5f5;
      color: #333;
      border: 1px solid #ddd;
  }
   .home-btn:hover {
      background-color: #e0e0e0;
  }

  .email-btn {
      background-color: #4caf50;
      color: white;
  }
  .email-btn:hover {
       background-color: #388e3c;
  }

  .email-input-container {
      margin-top: 1.5rem; /* More space */
      display: flex;
      justify-content: center;
      align-items: center; /* Align items vertically */
      gap: 0.5rem;
      flex-wrap: wrap; /* Allow wrapping on small screens */
  }

  .email-input {
      padding: 0.75rem;
      border-radius: 50px; /* Pill shape */
      border: 1px solid #ddd;
      min-width: 250px;
      flex-grow: 1; /* Allow input to grow */
      max-width: 400px; /* Limit max width */
  }

  .send-email-btn {
      background-color: #3f51b5;
      color: white;
  }
  .send-email-btn:hover {
      background-color: #303f9f;
  }

  /* Responsive adjustments */
  @media (max-width: 600px) {
      .results-page {
          padding: 1rem;
          margin: 1rem;
      }
      .result-main-title {
          font-size: 1.4rem;
      }
      .result-message {
          padding: 1rem;
      }
      .result-actions {
          flex-direction: column;
          gap: 0.8rem;
      }
      .restart-btn, .home-btn, .email-btn {
          width: 100%;
          justify-content: center;
      }
      .email-input-container {
          flex-direction: column;
          align-items: stretch;
      }
      .email-input {
          min-width: auto;
          width: 100%;
      }
      .send-email-btn {
          width: 100%;
          justify-content: center;
      }
  }
</style>