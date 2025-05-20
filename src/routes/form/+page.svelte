

<script >
    import Select from "../../components/globals/select/index.svelte"
    import { getCurso } from "../ultils/cursos.ultils"
    
    import { generoList,instituicaoList, periodoList } from "./data";
    import axios from "axios";



    let stage = $state(0);

        let dataForm = $state({
            name: null,
            age: null,
            period: null,
            institution: null,
            gender: null,
            graduation: null,
            cep: null,
            state: null,
            city: null
        });

    let graduacaoList = $state();
    (async () => graduacaoList = await getCurso() )()

    async function sendForm() {
        console.log(dataForm);

        const query = new URLSearchParams(dataForm).toString();

        window.location.href = `/quiz?${query}`;
    }


    let loadLocation = $state(false);
    function getLocation() {
        loadLocation = true;

        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(async (e) => {
                const {data} = await axios(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${e.coords.latitude}&lon=${e.coords.longitude}`);

                dataForm.cep = data.address.postcode;
                dataForm.state = data.address.state;
                dataForm.city = data.address.city;

                loadLocation = false;
            });
        }else {
            loadLocation = false;
        }
    }

    function next() {
        if(stage == 0) {
            if(!dataForm.name) return document.querySelector("#name").classList.add("inputError");
            if(!dataForm.age) return document.querySelector("#age").classList.add("inputError");
            if(!dataForm.gender) return document.querySelector("#period").classList.add("selectError");

            stage++;
        }

        if(stage == 1) {
            if(!dataForm.graduation) return document.querySelector("#graduation").classList.add("selectError");
            if(!dataForm.period) return document.querySelector("#period").classList.add("selectError");
            if(!dataForm.institution) return document.querySelector("#institution").classList.add("selectError");        

            stage++;
        }

        if(stage == 2) {
            if(!dataForm.cep) return document.querySelector("#cep").classList.add("inputError");
            if(!dataForm.state) return document.querySelector("#state").classList.add("inputError");
            if(!dataForm.city) return document.querySelector("#city").classList.add("inputError");
            
            sendForm();
        }

    }

</script>

<main id="page-form" class="page">
    
    <div class="container">
        <h1>Anamnese da ansiedade</h1>

        <div>
            {#if stage == 0}
                <div class="grup">
                    <input 
                        oninput={e => {
                            dataForm.name = e.currentTarget.value;
                            document.querySelector("#name").classList.remove("inputError");
                        }} 
                        placeholder="Nome ou apelido (opcional)" 
                        type="text"
                        id="name"
                    >
                    
                    <input 
                        oninput={e => {
                            dataForm.age = e.currentTarget.value;
                            document.querySelector("#age").classList.remove("inputError");
                        }} 
                        placeholder="Idade" 
                        type="number"
                        id="age" 
                    >

                    <Select 
                        label="Gêneros"
                        onSelect={e => {
                            dataForm.gender = e;
                            document.querySelector("#period").classList.remove("selectError");
                        }} 
                        id="period"
                        typeInput="text"
                        input={true}
                        options={generoList} 
                        select={dataForm.gender} 
                    />
                </div>
            {/if}

            {#if stage == 1}
                <div class="grup">
                    <Select 
                        label="Qual graduação você está cursando?"
                        onSelect={(e) => {
                            dataForm.graduation = e;
                            document.querySelector("#graduation").classList.remove("selectError");
                        }} 
                        typeInput="text"
                        input={true}
                        options={graduacaoList} 
                        select={dataForm.graduation} 
                        id="graduation"
                    />

                    <Select 
                        label="Em qual período da faculdade você se encontra?"
                        onSelect={(e) => {
                            dataForm.period = e;
                            document.querySelector("#period").classList.remove("selectError");
                        }} 
                        typeInput="number"
                        input={true}
                        options={periodoList} 
                        select={dataForm.period} 
                        id="period"
                    />

                    <Select 
                        label="Instituição"
                        onSelect={(e) => {
                            dataForm.institution = e;
                            document.querySelector("#period").classList.remove("selectError");
                        }} 
                        typeInput="text"
                        input={true}
                        options={instituicaoList} 
                        select={dataForm.period} 
                        id="institution"
                    />
                </div>
            {/if}     

            {#if stage == 2}
                <div class="grup">
                    <div id="cep">
                        <input 
                            oninput={e => {
                                dataForm.cep = e.currentTarget.value
                                document.querySelector("#cep").classList.remove("inputError");
                            }} 
                            name="cep" 
                            placeholder="cep" 
                            type="text"
                            value={dataForm.cep}
                            disabled={loadLocation}
                            maxlength="9"
                            id="cep"
                        >
    
                        {#if !dataForm.cep}
                            <button
                                disabled={loadLocation}
                                onclick={() => getLocation()}
                            >
                                {#if !loadLocation}
                                    <img src="/svg/location.svg" alt="" srcset="">
                                {/if}

                                {#if loadLocation}
                                    <img src="/svg/tube-spinner.svg" alt="" srcset="">
                                {/if}
                            </button>
                        {/if}
                    </div>

                    <input 
                        oninput={e => {
                            dataForm.state = e.currentTarget.value;
                            document.querySelector("#cep").classList.remove("inputError");
                        }} 
                        name="estado" 
                        placeholder="Estado" 
                        type="text"
                        value={dataForm.estado}
                        disabled={loadLocation}
                        id="state"
                    >
                    
                    
                    <input 
                        oninput={e => {
                            dataForm.city = e.currentTarget.value;
                            document.querySelector("#city").classList.remove("inputError");
                        }} 
                        name="cidade" 
                        placeholder="Cidade" 
                        type="text"
                        value={dataForm.city}
                        disabled={loadLocation}
                        id="city"
                    >

                </div>

            {/if}     


            <div id="buttons">
                {#if stage > 0}
                    <button class="backButton" onclick={() => stage--} id="next-button">Voltar</button>
                {/if}
    
                <button 
                    class={stage < 1 ? "nextButton" : ""} 
                    onclick={() => next()} 
                    id="next-button"
                > Próximo </button>
            </div>
        </div>

    </div>
</main>

<style>
    @import "./styles.css";

</style>

