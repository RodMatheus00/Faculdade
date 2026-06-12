/* ===========================
   form.js - Validação do formulário
   =========================== */

const formulario = document.querySelector("#ecoForm");
const mensagem = document.querySelector("#mensagem");
const contador = document.querySelector("#contador");

const campos = {
  nome: document.querySelector("#nome"),
  email: document.querySelector("#email"),
  telefone: document.querySelector("#telefone"),
  cidade: document.querySelector("#cidade"),
  data: document.querySelector("#data"),
  perfil: document.querySelector("#perfil"),
  mensagem: document.querySelector("#mensagem"),
  termos: document.querySelector("#termos")
};

function mostrarErro(campo, texto) {
  const elementoErro = document.querySelector(`#erro-${campo}`);
  const elementoCampo = campos[campo];

  if (elementoErro) {
    elementoErro.textContent = texto;
  }

  if (elementoCampo && elementoCampo.type !== "checkbox") {
    elementoCampo.classList.toggle("invalido", texto.length > 0);
  }
}

function validarNome() {
  const valor = campos.nome.value.trim();
  const regexNome = /^[A-Za-zÀ-ÖØ-öø-ÿ' ]{3,60}$/;

  if (!valor) {
    mostrarErro("nome", "Informe seu nome completo.");
    return false;
  }

  if (!regexNome.test(valor)) {
    mostrarErro("nome", "Use apenas letras e pelo menos 3 caracteres.");
    return false;
  }

  mostrarErro("nome", "");
  return true;
}

function validarEmail() {
  const valor = campos.email.value.trim();
  const regexEmail = /^[^\s@]+@[^\s@]+\.[^\s@]{2,}$/;

  if (!valor) {
    mostrarErro("email", "Informe seu e-mail.");
    return false;
  }

  if (!regexEmail.test(valor)) {
    mostrarErro("email", "Digite um e-mail válido.");
    return false;
  }

  mostrarErro("email", "");
  return true;
}

function formatarTelefone() {
  let valor = campos.telefone.value.replace(/\D/g, "");
  valor = valor.slice(0, 11);

  if (valor.length > 10) {
    valor = valor.replace(/(\d{2})(\d{5})(\d{4})/, "($1) $2-$3");
  } else if (valor.length > 6) {
    valor = valor.replace(/(\d{2})(\d{4})(\d{0,4})/, "($1) $2-$3");
  } else if (valor.length > 2) {
    valor = valor.replace(/(\d{2})(\d{0,5})/, "($1) $2");
  }

  campos.telefone.value = valor;
}

function validarTelefone() {
  const valor = campos.telefone.value.trim();
  const regexTelefone = /^\(\d{2}\) \d{4,5}-\d{4}$/;

  if (!valor) {
    mostrarErro("telefone", "Informe seu telefone.");
    return false;
  }

  if (!regexTelefone.test(valor)) {
    mostrarErro("telefone", "Use o formato (11) 99999-9999.");
    return false;
  }

  mostrarErro("telefone", "");
  return true;
}

function validarCidade() {
  const valor = campos.cidade.value.trim();
  const regexCidade = /^[A-Za-zÀ-ÖØ-öø-ÿ' ]{2,50}$/;

  if (!valor) {
    mostrarErro("cidade", "Informe sua cidade.");
    return false;
  }

  if (!regexCidade.test(valor)) {
    mostrarErro("cidade", "A cidade deve conter apenas letras.");
    return false;
  }

  mostrarErro("cidade", "");
  return true;
}

function validarData() {
  const valor = campos.data.value;

  if (!valor) {
    mostrarErro("data", "Escolha uma data para começar.");
    return false;
  }

  const hoje = new Date();
  hoje.setHours(0, 0, 0, 0);

  const dataEscolhida = new Date(`${valor}T00:00:00`);

  if (dataEscolhida < hoje) {
    mostrarErro("data", "A data não pode estar no passado.");
    return false;
  }

  mostrarErro("data", "");
  return true;
}

function validarPerfil() {
  if (!campos.perfil.value) {
    mostrarErro("perfil", "Selecione seu perfil.");
    return false;
  }

  mostrarErro("perfil", "");
  return true;
}

function validarInteresse() {
  const escolhido = document.querySelector("input[name='interesse']:checked");
  const erro = document.querySelector("#erro-interesse");

  if (!escolhido) {
    erro.textContent = "Escolha uma área de interesse.";
    return false;
  }

  erro.textContent = "";
  return true;
}

function validarTemas() {
  const marcados = document.querySelectorAll("input[name='temas']:checked");
  const erro = document.querySelector("#erro-temas");

  if (marcados.length === 0) {
    erro.textContent = "Marque pelo menos um tema.";
    return false;
  }

  erro.textContent = "";
  return true;
}

function validarMensagem() {
  const valor = campos.mensagem.value.trim();

  if (!valor) {
    mostrarErro("mensagem", "Escreva um comentário.");
    return false;
  }

  if (valor.length < 20) {
    mostrarErro("mensagem", "O comentário precisa ter pelo menos 20 caracteres.");
    return false;
  }

  mostrarErro("mensagem", "");
  return true;
}

function validarTermos() {
  if (!campos.termos.checked) {
    mostrarErro("termos", "Você precisa confirmar os dados.");
    return false;
  }

  mostrarErro("termos", "");
  return true;
}

function validarFormulario() {
  const validacoes = [
    validarNome(),
    validarEmail(),
    validarTelefone(),
    validarCidade(),
    validarData(),
    validarPerfil(),
    validarInteresse(),
    validarTemas(),
    validarMensagem(),
    validarTermos()
  ];

  return validacoes.every(Boolean);
}

if (formulario) {
  campos.telefone.addEventListener("input", () => {
    formatarTelefone();
    validarTelefone();
  });

  campos.nome.addEventListener("blur", validarNome);
  campos.email.addEventListener("blur", validarEmail);
  campos.cidade.addEventListener("blur", validarCidade);
  campos.data.addEventListener("change", validarData);
  campos.perfil.addEventListener("change", validarPerfil);
  campos.mensagem.addEventListener("input", () => {
    contador.textContent = `${campos.mensagem.value.length}/300`;
    validarMensagem();
  });

  document.querySelectorAll("input[name='interesse']").forEach((radio) => {
    radio.addEventListener("change", validarInteresse);
  });

  document.querySelectorAll("input[name='temas']").forEach((checkbox) => {
    checkbox.addEventListener("change", validarTemas);
  });

  campos.termos.addEventListener("change", validarTermos);

  formulario.addEventListener("submit", (evento) => {
    if (!validarFormulario()) {
      evento.preventDefault();
      const primeiroErro = document.querySelector(".invalido");
      if (primeiroErro) {
        primeiroErro.focus();
      }
    }
  });

  formulario.addEventListener("reset", () => {
    setTimeout(() => {
      document.querySelectorAll(".erro").forEach((erro) => erro.textContent = "");
      document.querySelectorAll(".invalido").forEach((campo) => campo.classList.remove("invalido"));
      contador.textContent = "0/300";
    }, 0);
  });
}
