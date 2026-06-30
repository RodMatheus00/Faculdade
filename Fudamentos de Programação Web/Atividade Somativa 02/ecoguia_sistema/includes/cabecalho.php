<?php
require_once __DIR__ . '/../config/conexao.php';

if (!isset($tituloPagina)) {
    $tituloPagina = 'EcoGuia';
}

$logado = function_exists('usuarioLogado') ? usuarioLogado() : false;
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($tituloPagina) ?> | EcoGuia</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link rel="stylesheet" href="<?= BASE_URL ?>/css/estilo.css">
</head>
<body class="d-flex flex-column min-vh-100">
<nav class="navbar navbar-expand-lg navbar-dark bg-ecoguia shadow-sm">
    <div class="container">
        <a class="navbar-brand fw-bold" href="<?= BASE_URL ?>/index.html">
            <i class="bi bi-tree-fill me-2"></i>EcoGuia
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#menuPrincipal">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="menuPrincipal">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link" href="<?= BASE_URL ?>/index.html">Inicio</a>
                </li>
                <?php if ($logado): ?>
                <li class="nav-item">
                    <a class="nav-link" href="<?= BASE_URL ?>/dashboard.php">Painel</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?= BASE_URL ?>/categorias/listar.php">Categorias</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?= BASE_URL ?>/projetos/listar.php">Projetos</a>
                </li>
                <?php endif; ?>
            </ul>
            <ul class="navbar-nav">
                <?php if ($logado): ?>
                <li class="nav-item">
                    <span class="nav-link text-white-50">
                        <i class="bi bi-person-circle me-1"></i><?= htmlspecialchars(nomeUsuario()) ?>
                    </span>
                </li>
                <li class="nav-item">
                    <a class="nav-link btn btn-outline-light btn-sm ms-lg-2 px-3" href="<?= BASE_URL ?>/auth/logout.php">
                        <i class="bi bi-box-arrow-right me-1"></i>Sair
                    </a>
                </li>
                <?php else: ?>
                <li class="nav-item">
                    <a class="nav-link" href="<?= BASE_URL ?>/auth/login.php">Entrar</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link btn btn-outline-light btn-sm ms-lg-2 px-3" href="<?= BASE_URL ?>/auth/cadastro.php">Cadastrar</a>
                </li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>
<main class="flex-grow-1 py-4">
