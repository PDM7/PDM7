<script>
  import Quiz from '$lib/quiz.svelte';
</script>

<main>
  <div class="content">
    <h1 class="title">Svelte Quiz</h1>
    <p>
      is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a?
    </p>
    <a href="/quiz" class="btn">INICIAR</a>
  </div>
</main>

<style>
  main {
    background-color: #F6FBFF;
    height: 100vh;
    display: flex;
    align-items: center;
    justify-content: start;
  }

  .content {
    max-width: 40vw;
    display: flex;
    flex-direction: column;
    align-items: start;
    padding-left: 5vw;
  }

  p {
    max-width: 100%;
    margin-top: 0;
    font-size: 1.5em;
    font-weight: 400;
    margin-bottom: 10%;
  }

  .btn {
    display: inline-block;
    padding: 20px 90px;
    font-size: 1.5em;
    font-weight: 900;
    color: white;
    background-color: #3E7BFF;
    border: none;
    border-radius: 10px;
    text-decoration: none;
    transition: 0.3s;
  }

  .btn:hover {
    background-color: #2D5ECF;
  }
</style>
