<script>
    import Quiz from '$lib/quiz.svelte';
    
    import psicologiaData from "../../../data/psicologia.data.js";
    import colmeiabData from "../../../data/colmeiab.data.js";

    const props = $props();

    const data = {
        "550e8400-e29b-41d4-a716-446655440000": psicologiaData,
        "f47ac10b-58cc-4372-a567-0e02b2c3d479": colmeiabData
    };
</script>
  
<main>
  <div class="content">
    <Quiz questions={data[props.data.id]}/> 
  </div>
</main>
