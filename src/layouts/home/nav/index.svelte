<script>
    import { text } from "@sveltejs/kit"
    import { menu } from "./data";
</script>

<nav id="nav">
    <div class="container">

        <img id="logo" src="https://github.com/user-attachments/assets/4c8bf738-6aa6-46ad-be29-66ba6a689b64" alt="logo PDM7">
        
        <ul id="menu">
            {#each menu as _data}
                <li><a href={_data.href}> {_data.text} </a></li>
            {/each}
        </ul>
    </div>
</nav>

<style>
    @import "./styles.css";
</style>