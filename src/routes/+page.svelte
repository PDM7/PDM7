<script>
  import { base } from "$app/paths";
  import { fade, fly } from 'svelte/transition';
  import { onMount } from 'svelte';
  import { Route, Router } from "svelte-routing"
  
  let loaded = false;
  
  onMount(() => {
    loaded = true;
  });

  import Main from "./main/+page.svelte";
  import About from "./about/+page.svelte";
</script>


<main class:loaded>
  <Router basepath="">
    <Route path="/" ><Main/></Route>
    <Route path="/about" component={About}/>
  </Router>
</main>


