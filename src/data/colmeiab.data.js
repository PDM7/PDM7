import { base } from "$app/paths";

const questions = [
  {
    id: 1,
    type: "multiple",
    difficulty: "easy",
    category: "Autoestima",
    question: "Como você descreveria sua autoestima hoje?",
    answers: [
      { id: 1, text: "Muito baixa", score: 1 },
      { id: 2, text: "Baixa", score: 2 },
      { id: 3, text: "Moderada", score: 3 },
      { id: 4, text: "Alta", score: 4 },
      { id: 5, text: "Muito alta", score: 5 }
    ]
  },
  {
    id: 2,
    type: "multiple",
    difficulty: "easy",
    category: "Autoestima",
    question: "Você já esteve melhor ou pior em relação a ela?",
    answers: [
      { id: 1, text: "Muito pior", score: 1 },
      { id: 2, text: "Pior", score: 2 },
      { id: 3, text: "Semelhante", score: 3 },
      { id: 4, text: "Melhor", score: 4 },
      { id: 5, text: "Muito melhor", score: 5 }
    ]
  },
  {
    id: 3,
    type: "multiple",
    difficulty: "easy",
    category: "Autoestima",
    question: "Você sente que sua autoestima prejudica seu psicológico? E suas relações sociais?",
    answers: [
      { id: 1, text: "Sim, muito", score: 1 },
      { id: 2, text: "Sim, um pouco", score: 2 },
      { id: 3, text: "Não tenho certeza", score: 3 },
      { id: 4, text: "Não, um pouco", score: 4 },
      { id: 5, text: "Não, de forma alguma", score: 5 }
    ]
  },
  {
    id: 4,
    type: "multiple",
    difficulty: "easy",
    category: "Autoestima",
    question: "Como você lida com os dias que sua autoestima esteja ruim?",
    answers: [
      { id: 1, text: "Me isolo", score: 1 },
      { id: 2, text: "Busco distrações", score: 2 },
      { id: 3, text: "Procuro apoio em outras pessoas", score: 3 },
      { id: 4, text: "Tento identificar os motivos e agir", score: 4 },
      { id: 5, text: "Outro", score: 5 }
    ]
  },
  {
    id: 5,
    type: "multiple",
    difficulty: "easy",
    category: "Autoestima",
    question: "Você costuma se comparar com outras pessoas? Como isso afeta sua autoestima?",
    answers: [
      { id: 1, text: "Nunca me comparo", score: 5 },
      { id: 2, text: "Raramente, e não me afeta", score: 4 },
      { id: 3, text: "Às vezes, e me sinto um pouco pior", score: 3 },
      { id: 4, text: "Frequentemente, e me sinto pior", score: 2 },
      { id: 5, text: "Frequentemente, e me sinto muito pior", score: 1 }
    ]
  },
  {
    id: 6,
    type: "multiple",
    difficulty: "easy",
    category: "Autoestima",
    question: "Há alguma situação específica que abala sua autoestima com frequência?",
    answers: [
      { id: 1, text: "Nenhuma específica", score: 5 },
      { id: 2, text: "Críticas", score: 3 },
      { id: 3, text: "Rejeição", score: 2 },
      { id: 4, text: "Fracassos", score: 1 },
      { id: 5, text: "Outra", score: 3 }
    ]
  },
  {
    id: 7,
    type: "multiple",
    difficulty: "easy",
    category: "Autoestima",
    question: "Como você reage diante de críticas? E de elogios?",
    answers: [
      { id: 1, text: "Fico muito mal com críticas e desconfio de elogios", score: 1 },
      { id: 2, text: "Fico chateado(a) com críticas e aceito elogios", score: 2 },
      { id: 3, text: "Analiso as críticas e aceito os elogios", score: 3 },
      { id: 4, text: "Recebo bem críticas construtivas e valorizo os elogios", score: 4 },
      { id: 5, text: "Gosto de críticas que me ajudam a melhorar e os elogios me motivam", score: 5 }
    ]
  },
  {
    id: 8,
    type: "multiple",
    difficulty: "easy",
    category: "Autoestima",
    question: "Você sente que seu valor depende da aprovação dos outros?",
    answers: [
      { id: 1, text: "Sim, totalmente", score: 1 },
      { id: 2, text: "Sim, em grande parte", score: 2 },
      { id: 3, text: "Um pouco", score: 3 },
      { id: 4, text: "Raramente", score: 4 },
      { id: 5, text: "Não, de forma alguma", score: 5 }
    ]
  },
  {
    id: 9,
    type: "multiple",
    difficulty: "easy",
    category: "Autoestima",
    question: "O que você faz quando está se sentindo inseguro(a) ou em dúvida sobre si?",
    answers: [
      { id: 1, text: "Desisto de tentar", score: 1 },
      { id: 2, text: "Peço opinião de outros", score: 2 },
      { id: 3, text: "Reflito sobre minhas qualidades", score: 3 },
      { id: 4, text: "Busco informações e me preparo mais", score: 4 },
      { id: 5, text: "Outro", score: 3 }
    ]
  }
];

export default questions;