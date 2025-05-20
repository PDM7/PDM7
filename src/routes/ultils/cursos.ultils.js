import axios from "axios";

export async function getCurso() {
    try {
        // api para buscar os cursos da fagoc
        const { data } = await axios("https://unifagoc.apprbs.com.br/api/getOffersAllowed", {
            method: "POST",
            data: {
                authKey: "PhOBj6zudyKbc5lrh8es", // essa código de autenticação é publico
                id: "83",
                isPreview: 0
            }
        });

        return data.dados.map((_data) => {
            return { value: _data.tituloCurso, label: _data.tituloCurso }}
        );
    }catch(e) {
        console.error(e);
    }
}