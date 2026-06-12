/* ===========================
   action.js - Leitura dos dados via GET
   =========================== */

const areaDados = document.querySelector("#dadosRecebidos");
const areaSugestao = document.querySelector("#sugestao");

function criarLinha(rotulo, valor) {
  const linha = document.createElement("div");
  linha.className = "linha-dado";

  const titulo = document.createElement("strong");
  titulo.textContent = rotulo;

  const conteudo = document.createElement("span");
  conteudo.textContent = valor || "Não informado";

  linha.appendChild(titulo);
  linha.appendChild(conteudo);

  return linha;
}

function formatarData(dataIso) {
  if (!dataIso) {
    return "";
  }

  const partes = dataIso.split("-");
  if (partes.length !== 3) {
    return dataIso;
  }

  return `${partes[2]}/${partes[1]}/${partes[0]}`;
}

function gerarSugestao(perfil, interesse) {
  const perfilTexto = perfil || "iniciante";
  const interesseTexto = interesse || "sustentabilidade";

  if (interesseTexto.includes("água")) {
    return `Sugestão para perfil ${perfilTexto}: comece medindo o tempo de banho e reaproveite água sempre que possível.`;
  }

  if (interesseTexto.includes("Energia")) {
    return `Sugestão para perfil ${perfilTexto}: revise o uso de aparelhos em modo espera e priorize luz natural durante o dia.`;
  }

  if (interesseTexto.includes("Reciclagem")) {
    return `Sugestão para perfil ${perfilTexto}: separe resíduos secos e orgânicos e identifique pontos de coleta na sua cidade.`;
  }

  return `Sugestão para perfil ${perfilTexto}: escolha uma pequena atitude sustentável e acompanhe sua evolução por uma semana.`;
}

function exibirDados() {
  const parametros = new URLSearchParams(window.location.search);

  if ([...parametros.keys()].length === 0) {
    areaDados.appendChild(criarLinha("Aviso", "Nenhum dado foi recebido. Acesse o formulário e envie suas respostas."));
    areaSugestao.textContent = "Quando o formulário for enviado por GET, os dados aparecerão automaticamente nesta página.";
    return;
  }

  const temas = parametros.getAll("temas").join(", ");

  const dados = [
    ["Nome", parametros.get("nome")],
    ["E-mail", parametros.get("email")],
    ["Telefone", parametros.get("telefone")],
    ["Cidade", parametros.get("cidade")],
    ["Data para começar", formatarData(parametros.get("data"))],
    ["Perfil sustentável", parametros.get("perfil")],
    ["Área de interesse", parametros.get("interesse")],
    ["Temas escolhidos", temas],
    ["Comentário", parametros.get("mensagem")],
    ["Confirmação", parametros.get("termos")]
  ];

  dados.forEach(([rotulo, valor]) => {
    areaDados.appendChild(criarLinha(rotulo, valor));
  });

  areaSugestao.textContent = gerarSugestao(parametros.get("perfil"), parametros.get("interesse"));
}

if (areaDados && areaSugestao) {
  exibirDados();
}
