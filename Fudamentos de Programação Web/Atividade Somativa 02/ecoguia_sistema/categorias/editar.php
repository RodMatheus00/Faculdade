<?php
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../config/conexao.php';

exigirLogin();

$id = (int) ($_GET['id'] ?? 0);
$erro = '';

$stmt = $pdo->prepare('SELECT * FROM categorias WHERE id = ?');
$stmt->execute([$id]);
$categoria = $stmt->fetch();

if (!$categoria) {
    header('Location: ' . BASE_URL . '/categorias/listar.php?msg=Categoria+nao+encontrada.&tipo=danger');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome      = trim($_POST['nome'] ?? '');
    $descricao = trim($_POST['descricao'] ?? '');

    if ($nome === '') {
        $erro = 'O nome da categoria e obrigatorio.';
    } else {
        $update = $pdo->prepare('UPDATE categorias SET nome = ?, descricao = ? WHERE id = ?');
        $update->execute([$nome, $descricao, $id]);

        header('Location: ' . BASE_URL . '/categorias/listar.php?msg=Categoria+atualizada+com+sucesso!&tipo=success');
        exit;
    }
}

$tituloPagina = 'Editar Categoria';
require_once __DIR__ . '/../includes/cabecalho.php';
?>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-lg-7">
            <div class="card card-ecoguia">
                <div class="card-header">
                    <i class="bi bi-pencil me-2"></i>Editar categoria #<?= (int) $categoria['id'] ?>
                </div>
                <div class="card-body p-4">
                    <?php if ($erro): ?>
                        <div class="alert alert-danger"><?= htmlspecialchars($erro) ?></div>
                    <?php endif; ?>

                    <form method="post" action="" data-validar novalidate>
                        <div class="mb-3">
                            <label for="nome" class="form-label">Nome *</label>
                            <input type="text" class="form-control" id="nome" name="nome"
                                   data-regra="obrigatorio|min:3" required maxlength="100"
                                   value="<?= htmlspecialchars($_POST['nome'] ?? $categoria['nome']) ?>">
                        </div>
                        <div class="mb-3">
                            <label for="descricao" class="form-label">Descricao</label>
                            <textarea class="form-control" id="descricao" name="descricao" rows="4"><?= htmlspecialchars($_POST['descricao'] ?? $categoria['descricao'] ?? '') ?></textarea>
                        </div>
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-ecoguia">
                                <i class="bi bi-check-lg me-1"></i>Atualizar
                            </button>
                            <a href="<?= BASE_URL ?>/categorias/listar.php" class="btn btn-outline-secondary">Cancelar</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/rodape.php'; ?>
