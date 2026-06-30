<?php
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../config/conexao.php';

exigirLogin();

$id = (int) ($_GET['id'] ?? 0);

if ($id <= 0) {
    header('Location: ' . BASE_URL . '/categorias/listar.php?msg=ID+invalido.&tipo=danger');
    exit;
}

$stmt = $pdo->prepare('SELECT id, nome FROM categorias WHERE id = ?');
$stmt->execute([$id]);
$categoria = $stmt->fetch();

if (!$categoria) {
    header('Location: ' . BASE_URL . '/categorias/listar.php?msg=Categoria+nao+encontrada.&tipo=danger');
    exit;
}

$verifica = $pdo->prepare('SELECT COUNT(*) AS total FROM projetos WHERE categoria_id = ?');
$verifica->execute([$id]);
$totalProjetos = (int) $verifica->fetch()['total'];

if ($totalProjetos > 0) {
    header('Location: ' . BASE_URL . '/categorias/listar.php?msg=Nao+e+possivel+excluir:+existem+projetos+vinculados.&tipo=danger');
    exit;
}

$delete = $pdo->prepare('DELETE FROM categorias WHERE id = ?');
$delete->execute([$id]);

header('Location: ' . BASE_URL . '/categorias/listar.php?msg=Categoria+excluida+com+sucesso!&tipo=success');
exit;
