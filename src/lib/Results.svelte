<script>
  export let scoreAnsiedade;
  export let resetQuiz;
  import { base } from "$app/paths";
</script>

<div class="results-page">
  <div class="confetti"></div>
  <div class="confetti"></div>
  <div class="confetti"></div>
  <div class="confetti"></div>
  <div class="confetti"></div>
  
  <svg class="trophy-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
      <circle cx="12" cy="8" r="7"></circle>
      <polyline points="8.21 13.89 7 23 12 20 17 23 15.79 13.88"></polyline>
  </svg>
  
  <h2>Envio finalizado! 🎉</h2>
  
  <div class="result-container">
      {#if scoreAnsiedade <= 12}
          <div class="result-message result-good">
              <h3 class="result-main-title">Ansiedade Leve</h3>
              <p>A ansiedade leve é uma sensação natural de nervosismo ou preocupação que acontece em situações como provas, entrevistas ou mudanças importantes na vida. Ela pode causar um pouco de inquietação ou preocupação, mas não atrapalha as atividades diárias, inclusive é saudável e ajuda nos processos psíquicos.</p>
          </div>
      {:else if scoreAnsiedade <= 25}
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
  </div>
</div>

<style>
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
      width: 10px;
      height: 10px;
      background-color: #f00;
      opacity: 0.5;
      animation: confetti 5s ease-in-out infinite;
  }

  @keyframes confetti {
      0% { transform: translateY(0) rotate(0deg); }
      100% { transform: translateY(100vh) rotate(720deg); }
  }

  .trophy-icon {
      width: 80px;
      height: 80px;
      margin: 0 auto 1rem;
      display: block;
  }

  .result-container {
      text-align: center;
      margin: 1.5rem auto;
      max-width: 600px;
  }

  .result-main-title {
      color: #2c3e50;
      font-size: 1.8rem;
      margin-bottom: 1.5rem;
      text-align: center;
      font-weight: 600;
  }

  .result-message {
      padding: 2rem;
      border-radius: 8px;
      margin: 1rem 0;
      text-align: left;
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
  }

  .restart-btn, .home-btn {
      padding: 0.75rem 1.5rem;
      border-radius: 4px;
      display: inline-flex;
      align-items: center;
      gap: 0.5rem;
      text-decoration: none;
      cursor: pointer;
      transition: all 0.3s ease;
  }

  .restart-btn:hover, .home-btn:hover {
      transform: translateY(-2px);
      box-shadow: 0 4px 8px rgba(0,0,0,0.1);
  }

  .restart-btn {
      background-color: #3f51b5;
      color: white;
      border: none;
  }

  .home-btn {
      background-color: #f5f5f5;
      color: #333;
      border: 1px solid #ddd;
  }
</style>