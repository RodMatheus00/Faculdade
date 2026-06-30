<?php
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../config/conexao.php';

exigirLogin();

$erro = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome      = trim($_POST['nome'] ?? '');
    $descricao = trim($_POST['descricao'] ?? '');

    if ($nome === '') {
        $erro = 'O nome da categoria e obrigatorio.';
    } else {
        $stmt = $pdo->prepare('INSERT INTO categorias (nome, descricao) VALUES (?, ?)');
        $stmt->execute([$nome, $descricao]);

        header('Location: ' . BASE_URL . '/categorias/listar.php?msg=Categoria+incluida+com+sucesso!&tipo=success');
        exit;
    }
}

$tituloPagina = 'Nova Categoria';
require_once __DIR__ . '/../includes/cabecalho.php';
?>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-lg-7">
            <div class="card card-ecoguia">
                <div class="card-header">
                    <i class="bi bi-plus-lg me-2"></i>Nova categoria
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
                                   value="<?= htmlspecialchars($_POST['nome'] ?? '') ?>">
                        </div>
                        <div class="mb-3">
                            <label for="descricao" class="form-label">Descricao</label>
                            <textarea class="form-control" id="descricao" name="descricao" rows="4"><?= htmlspecialchars($_POST['descricao'] ?? '') ?></textarea>
                        </div>
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-ecoguia">
                                <i class="bi bi-check-lg me-1"></i>Salvar
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
