

<script>
    const { options, select, onSelect, input, typeInput, label, id } = $props();
    let isOpen = $state(false);
</script>


<div id={id} class="select">
    <!-- svelte-ignore a11y_no_static_element_interactions -->
    <!-- svelte-ignore a11y_click_events_have_key_events -->
    <div onclick={() => {isOpen = !isOpen}} id="label">
        {!select ?  label : select}  
    </div>

    {#if isOpen}
        <div id="list"> 
            {#each options as _data}
                <button 
                    type="button" 
                    onclick={() => {
                        onSelect(_data.value);
                        isOpen = !isOpen;
                    }}>{_data.label}</button>                
            {/each}
            
            {#if input} 
                <div id="other">
                    <input 
                        placeholder="Outro" 
                        type={typeInput ? typeInput : "text"} 
                        oninput="{(e) => onSelect(e.currentTarget.value)}"
                    >
                </div>
            {/if}
        </div>
    {/if}
</div>

<style> 
    @import "./styles.css";
</style>