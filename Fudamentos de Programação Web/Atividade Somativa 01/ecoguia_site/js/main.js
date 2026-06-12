/* ===========================
   main.js - Interações gerais
   =========================== */

const menuBotao = document.querySelector("#menuBotao");
const menuLinks = document.querySelector("#menuLinks");
const temaBotao = document.querySelector("#temaBotao");

function aplicarTemaSalvo() {
  const tema = localStorage.getItem("tema-ecoguia");

  if (tema === "escuro") {
    document.body.classList.add("tema-escuro");
    temaBotao.textContent = "☀️";
  } else {
    temaBotao.textContent = "🌙";
  }
}

if (menuBotao && menuLinks) {
  menuBotao.addEventListener("click", () => {
    const aberto = menuLinks.classList.toggle("aberto");
    menuBotao.setAttribute("aria-expanded", aberto ? "true" : "false");
  });
}

if (temaBotao) {
  aplicarTemaSalvo();

  temaBotao.addEventListener("click", () => {
    document.body.classList.toggle("tema-escuro");
    const escuro = document.body.classList.contains("tema-escuro");

    localStorage.setItem("tema-ecoguia", escuro ? "escuro" : "claro");
    temaBotao.textContent = escuro ? "☀️" : "🌙";
  });
}

const cardsAnimados = document.querySelectorAll(".aparecer");

if ("IntersectionObserver" in window) {
  const observador = new IntersectionObserver((entradas) => {
    entradas.forEach((entrada) => {
      if (entrada.isIntersecting) {
        entrada.target.classList.add("visivel");
        observador.unobserve(entrada.target);
      }
    });
  }, { threshold: 0.18 });

  cardsAnimados.forEach((card) => observador.observe(card));
} else {
  cardsAnimados.forEach((card) => card.classList.add("visivel"));
}

const modalDica = document.querySelector("#modalDica");
const abrirDica = document.querySelector("#abrirDica");
const fecharDica = document.querySelector("#fecharDica");

function abrirModal() {
  if (modalDica) {
    modalDica.classList.add("aberto");
    modalDica.setAttribute("aria-hidden", "false");
  }
}

function fecharModal() {
  if (modalDica) {
    modalDica.classList.remove("aberto");
    modalDica.setAttribute("aria-hidden", "true");
  }
}

if (abrirDica && fecharDica && modalDica) {
  abrirDica.addEventListener("click", abrirModal);
  fecharDica.addEventListener("click", fecharModal);

  modalDica.addEventListener("click", (evento) => {
    if (evento.target === modalDica) {
      fecharModal();
    }
  });

  document.addEventListener("keydown", (evento) => {
    if (evento.key === "Escape") {
      fecharModal();
    }
  });
}
