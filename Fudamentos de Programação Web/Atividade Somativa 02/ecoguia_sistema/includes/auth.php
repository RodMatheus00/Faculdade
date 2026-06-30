<?php
/**
 * Funcoes de autenticacao e controle de sessao
 */

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function usuarioLogado(): bool
{
    return isset($_SESSION['usuario_id']);
}

function exigirLogin(): void
{
    if (!usuarioLogado()) {
        header('Location: ' . BASE_URL . '/auth/login.php?erro=acesso_negado');
        exit;
    }
}

function redirecionarSeLogado(): void
{
    if (usuarioLogado()) {
        header('Location: ' . BASE_URL . '/dashboard.php');
        exit;
    }
}

function nomeUsuario(): string
{
    return $_SESSION['usuario_nome'] ?? 'Usuario';
}

function emailUsuario(): string
{
    return $_SESSION['usuario_email'] ?? '';
}

function fazerLogin(array $usuario): void
{
    $_SESSION['usuario_id']    = $usuario['id'];
    $_SESSION['usuario_nome']  = $usuario['nome'];
    $_SESSION['usuario_email'] = $usuario['email'];
}

function fazerLogout(): void
{
    $_SESSION = [];

    if (ini_get('session.use_cookies')) {
        $params = session_get_cookie_params();
        setcookie(
            session_name(),
            '',
            time() - 42000,
            $params['path'],
            $params['domain'],
            $params['secure'],
            $params['httponly']
        );
    }

    session_destroy();
}
