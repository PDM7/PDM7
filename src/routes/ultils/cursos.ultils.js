import axios from "axios";

export async function getCurso() {
    try {
        // api para buscar os cursos da fagoc
        const data = ["Administração",
            "Pedagogia",
            "Enfermagem",
            "Ciências Contábeis",
            "Ciência da Computação",
            "Direito",
            "Farmácia",
            "Fisioterapia",
            "Educação Física",
            "Nutrição",
            "Odontologia",
            "Psicologia",
            "Biomedicina",
            "Medicina"];

        return data.map((item) => ({
            value: item,
            label: item
        }));
    }catch(e) {
        console.error(e);
    }
}