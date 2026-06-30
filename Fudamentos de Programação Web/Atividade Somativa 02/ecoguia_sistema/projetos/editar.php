<?php
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../config/conexao.php';

exigirLogin();

$id = (int) ($_GET['id'] ?? 0);
$erro = '';

$stmt = $pdo->prepare('SELECT * FROM projetos WHERE id = ?');
$stmt->execute([$id]);
$projeto = $stmt->fetch();

if (!$projeto) {
    header('Location: ' . BASE_URL . '/projetos/listar.php?msg=Projeto+nao+encontrado.&tipo=danger');
    exit;
}

$categorias = $pdo->query('SELECT id, nome FROM categorias ORDER BY nome ASC')->fetchAll();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome         = trim($_POST['nome'] ?? '');
    $descricao    = trim($_POST['descricao'] ?? '');
    $data_inicio  = $_POST['data_inicio'] ?? '';
    $status       = $_POST['status'] ?? 'planejado';
    $categoria_id = (int) ($_POST['categoria_id'] ?? 0);

    $statusValidos = ['planejado', 'em_andamento', 'concluido'];

    if ($nome === '' || $data_inicio === '' || $categoria_id <= 0) {
        $erro = 'Preencha todos os campos obrigatorios.';
    } elseif (!in_array($status, $statusValidos, true)) {
        $erro = 'Status invalido.';
    } else {
        $update = $pdo->prepare(
            'UPDATE projetos
             SET nome = ?, descricao = ?, data_inicio = ?, status = ?, categoria_id = ?
             WHERE id = ?'
        );
        $update->execute([$nome, $descricao, $data_inicio, $status, $categoria_id, $id]);

        header('Location: ' . BASE_URL . '/projetos/listar.php?msg=Projeto+atualizado+com+sucesso!&tipo=success');
        exit;
    }
}

$tituloPagina = 'Editar Projeto';
require_once __DIR__ . '/../includes/cabecalho.php';
?>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card card-ecoguia">
                <div class="card-header">
                    <i class="bi bi-pencil me-2"></i>Editar projeto #<?= (int) $projeto['id'] ?>
                </div>
                <div class="card-body p-4">
                    <?php if ($erro): ?>
                        <div class="alert alert-danger"><?= htmlspecialchars($erro) ?></div>
                    <?php endif; ?>

                    <form method="post" action="" data-validar novalidate>
                        <div class="mb-3">
                            <label for="nome" class="form-label">Nome do projeto *</label>
                            <input type="text" class="form-control" id="nome" name="nome"
                                   data-regra="obrigatorio|min:3" required maxlength="150"
                                   value="<?= htmlspecialchars($_POST['nome'] ?? $projeto['nome']) ?>">
                        </div>
                        <div class="mb-3">
                            <label for="descricao" class="form-label">Descricao</label>
                            <textarea class="form-control" id="descricao" name="descricao" rows="3"><?= htmlspecialchars($_POST['descricao'] ?? $projeto['descricao'] ?? '') ?></textarea>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="data_inicio" class="form-label">Data de inicio *</label>
                                <input type="date" class="form-control" id="data_inicio" name="data_inicio"
                                       data-regra="obrigatorio|data" required
                                       value="<?= htmlspecialchars($_POST['data_inicio'] ?? $projeto['data_inicio']) ?>">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="status" class="form-label">Status *</label>
                                <?php $statusAtual = $_POST['status'] ?? $projeto['status']; ?>
                                <select class="form-select" id="status" name="status" required>
                                    <option value="planejado" <?= $statusAtual === 'planejado' ? 'selected' : '' ?>>Planejado</option>
                                    <option value="em_andamento" <?= $statusAtual === 'em_andamento' ? 'selected' : '' ?>>Em andamento</option>
                                    <option value="concluido" <?= $statusAtual === 'concluido' ? 'selected' : '' ?>>Concluido</option>
                                </select>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="categoria_id" class="form-label">Categoria *</label>
                            <?php $catAtual = (int) ($_POST['categoria_id'] ?? $projeto['categoria_id']); ?>
                            <select class="form-select" id="categoria_id" name="categoria_id" required>
                                <?php foreach ($categorias as $cat): ?>
                                <option value="<?= (int) $cat['id'] ?>"
                                    <?= $catAtual === (int) $cat['id'] ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($cat['nome']) ?>
                                </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-ecoguia">
                                <i class="bi bi-check-lg me-1"></i>Atualizar
                            </button>
                            <a href="<?= BASE_URL ?>/projetos/listar.php" class="btn btn-outline-secondary">Cancelar</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/rodape.php'; ?>
