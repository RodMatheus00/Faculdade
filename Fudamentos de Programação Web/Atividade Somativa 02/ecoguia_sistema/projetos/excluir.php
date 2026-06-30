<?php
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../config/conexao.php';

exigirLogin();

$id = (int) ($_GET['id'] ?? 0);

if ($id <= 0) {
    header('Location: ' . BASE_URL . '/projetos/listar.php?msg=ID+invalido.&tipo=danger');
    exit;
}

$stmt = $pdo->prepare('SELECT id, nome FROM projetos WHERE id = ?');
$stmt->execute([$id]);
$projeto = $stmt->fetch();

if (!$projeto) {
    header('Location: ' . BASE_URL . '/projetos/listar.php?msg=Projeto+nao+encontrado.&tipo=danger');
    exit;
}

$delete = $pdo->prepare('DELETE FROM projetos WHERE id = ?');
$delete->execute([$id]);

header('Location: ' . BASE_URL . '/projetos/listar.php?msg=Projeto+excluido+com+sucesso!&tipo=success');
exit;
