/**
 * EcoGuia - Validacao de formularios com JavaScript
 */

document.addEventListener('DOMContentLoaded', function () {
    const formularios = document.querySelectorAll('[data-validar]');

    formularios.forEach(function (form) {
        form.addEventListener('submit', function (evento) {
            if (!validarFormulario(form)) {
                evento.preventDefault();
            }
        });

        const campos = form.querySelectorAll('[data-regra]');
        campos.forEach(function (campo) {
            campo.addEventListener('blur', function () {
                validarCampo(campo);
            });
        });
    });
});

function validarFormulario(form) {
    let valido = true;
    const campos = form.querySelectorAll('[data-regra]');

    campos.forEach(function (campo) {
        if (!validarCampo(campo)) {
            valido = false;
        }
    });

    return valido;
}

function validarCampo(campo) {
    const regras = campo.getAttribute('data-regra').split('|');
    const valor = campo.value.trim();
    let mensagem = '';

    removerErro(campo);

    for (const regra of regras) {
        if (regra === 'obrigatorio' && valor === '') {
            mensagem = 'Este campo e obrigatorio.';
            break;
        }

        if (regra === 'email' && valor !== '') {
            const regexEmail = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!regexEmail.test(valor)) {
                mensagem = 'Informe um e-mail valido.';
                break;
            }
        }

        if (regra.startsWith('min:')) {
            const minimo = parseInt(regra.split(':')[1], 10);
            if (valor.length < minimo) {
                mensagem = 'Minimo de ' + minimo + ' caracteres.';
                break;
            }
        }

        if (regra === 'data' && valor !== '') {
            const regexData = /^\d{4}-\d{2}-\d{2}$/;
            if (!regexData.test(valor)) {
                mensagem = 'Informe uma data valida (AAAA-MM-DD).';
                break;
            }
        }

        if (regra === 'senha' && valor !== '') {
            const regexSenha = /^(?=.*[A-Za-z])(?=.*\d).{6,}$/;
            if (!regexSenha.test(valor)) {
                mensagem = 'A senha deve ter no minimo 6 caracteres, com letras e numeros.';
                break;
            }
        }
    }

    if (mensagem !== '') {
        exibirErro(campo, mensagem);
        return false;
    }

    return true;
}

function exibirErro(campo, mensagem) {
    campo.classList.add('campo-invalido', 'is-invalid');

    const existente = campo.parentElement.querySelector('.mensagem-erro-campo');
    if (existente) {
        existente.textContent = mensagem;
        return;
    }

    const span = document.createElement('span');
    span.className = 'mensagem-erro-campo';
    span.textContent = mensagem;
    campo.parentElement.appendChild(span);
}

function removerErro(campo) {
    campo.classList.remove('campo-invalido', 'is-invalid');
    const mensagem = campo.parentElement.querySelector('.mensagem-erro-campo');
    if (mensagem) {
        mensagem.remove();
    }
}
