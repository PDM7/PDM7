<script>
  import { base } from "$app/paths";
  import { fade, fly } from 'svelte/transition';
  import { onMount } from 'svelte';
  
  let loaded = false;
  
  onMount(() => {
    loaded = true;
  });
</script>

<main class:loaded>
  <div class="content" in:fly={{ y: 100, duration: 800 }}>
    <h1 class="title" in:fade={{ delay: 200 }}>Anamnese da ansiedade</h1>
    <p in:fade={{ delay: 400 }}>
      Entendendo o impacto da ansiedade na sua rotina acadêmica.<br>
A participação é voluntária e todas as respostas serão tratadas com total sigilo e confidencialidade.
    </p>
    <a href="{base}/form" class="btn" in:fade={{ delay: 600 }}>INICIAR</a>
  </div>
  
  <div class="floating-shapes">
    <div class="shape circle"></div>
    <div class="shape triangle"></div>
    <div class="shape square"></div>
  </div>
</main>

<style>

main {
    background: linear-gradient(135deg, #F6FBFF 0%, #E6F3FF 100%);
    min-height: 100vh;
    display: flex;
    align-items: center;
    justify-content: start;
    position: relative;
    overflow: hidden;
    opacity: 0;
    transition: opacity 0.5s ease;
    /* Adicione estas linhas para prevenir overflow */
    width: 100%;
    box-sizing: border-box;
    padding: 20px; /* Adicione um padding padrão */
  }
  
  main.loaded {
    opacity: 1;
  }
  
  .content {
    max-width: min(40vw, 600px);
    display: flex;
    flex-direction: column;
    align-items: start;
    padding-left: 10vw;
    z-index: 2;
  }
  
  .title {
    font-size: clamp(2.5rem, 5vw, 4rem);
    font-weight: 800;
    color: #1A2B4D;
    margin-bottom: 1.5rem;
    background: linear-gradient(90deg, #3E7BFF 0%, #1A2B4D 100%);
    background-clip: text;
    -webkit-text-fill-color: transparent;
    line-height: 1.2;
  }
  
  p {
    max-width: 100%;
    margin: 0 0 2.5rem 0;
    font-size: clamp(1rem, 1.5vw, 1.5rem);
    font-weight: 400;
    color: #4A5568;
    line-height: 1.6;
  }
  
  .btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    padding: 1.2rem 4rem;
    font-size: clamp(1rem, 1.5vw, 1.5rem);
    font-weight: 700;
    color: white;
    background: linear-gradient(90deg, #3E7BFF 0%, #5A8EFF 100%);
    border: none;
    border-radius: 12px;
    text-decoration: none;
    transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
    box-shadow: 0 4px 15px rgba(62, 123, 255, 0.3);
    position: relative;
    overflow: hidden;
  }
  
  .btn:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 25px rgba(62, 123, 255, 0.4);
  }
  
  .btn:active {
    transform: translateY(1px);
  }
  
  .btn::after {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
    transition: 0.5s;
  }
  
  .btn:hover::after {
    left: 100%;
  }
  
  .floating-shapes {
    position: absolute;
    width: 100%;
    height: 100%;
    top: 0;
    left: 0;
    z-index: 1;
  }
  
  .shape {
    position: absolute;
    opacity: 0.1;
    transition: all 15s linear infinite;
  }
  
  .circle {
    width: 300px;
    height: 300px;
    border-radius: 50%;
    background: #3E7BFF;
    top: 20%;
    right: 10%;
    animation: float 15s ease-in-out infinite;
  }
  
  .triangle {
    width: 0;
    height: 0;
    border-left: 150px solid transparent;
    border-right: 150px solid transparent;
    border-bottom: 300px solid #FF6B6B;
    bottom: 10%;
    right: 25%;
    animation: float 12s ease-in-out infinite reverse;
  }
  
  .square {
    width: 200px;
    height: 200px;
    background: #4CAF50;
    top: 40%;
    left: 25%;
    animation: float 18s ease-in-out infinite;
  }
  
  @keyframes float {
    0%, 100% {
      transform: translateY(0) rotate(0deg);
    }
    50% {
      transform: translateY(-50px) rotate(5deg);
    }
  }
  
  @media (max-width: 1024px) {
    .content {
      max-width: 60vw;
      padding-left: 5vw;
    }
    
    .circle {
      right: -100px;
    }
  }
  
  @media (max-width: 768px) {
    main {
      justify-content: center;
      padding: 2rem;
    }
    
    .content {
      max-width: 90vw;
      padding-left: 0;
      align-items: center;
      text-align: center;
    }
    
    .shape {
      display: none;
    }
  }
</style>