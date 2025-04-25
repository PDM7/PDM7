<script>
  import { createEventDispatcher } from 'svelte'
  import { fade, fly } from 'svelte/transition'

  const dispatch = createEventDispatcher()
</script>

<div class="modal-bg" transition:fade>
  <div class="modal" transition:fly={{ y: -100 }}>
    <button
      on:click={() => {
        dispatch('close')
      }}
    >
      Fechar
    </button>
    <slot />
  </div>
</div>