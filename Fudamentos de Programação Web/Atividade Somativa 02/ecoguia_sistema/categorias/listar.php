<?php
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../config/conexao.php';

exigirLogin();

$categorias = $pdo->query('SELECT * FROM categorias ORDER BY nome ASC')->fetchAll();

$mensagem = $_GET['msg'] ?? '';
$tipoMsg  = $_GET['tipo'] ?? 'success';

$tituloPagina = 'Categorias';
require_once __DIR__ . '/../includes/cabecalho.php';
?>

<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
        <h1 class="h3 mb-0"><i class="bi bi-folder2-open me-2"></i>Categorias</h1>
        <a href="<?= BASE_URL ?>/categorias/inserir.php" class="btn btn-ecoguia">
            <i class="bi bi-plus-lg me-1"></i>Nova categoria
        </a>
    </div>

    <?php if ($mensagem): ?>
        <div class="alert alert-<?= htmlspecialchars($tipoMsg) ?> alert-dismissible fade show" role="alert">
            <?= htmlspecialchars($mensagem) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <div class="card card-ecoguia">
        <div class="card-body p-0">
            <?php if (empty($categorias)): ?>
                <p class="p-4 text-muted mb-0">Nenhuma categoria cadastrada.</p>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-hover mb-0 align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>ID</th>
                                <th>Nome</th>
                                <th>Descricao</th>
                                <th class="text-end">Acoes</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($categorias as $cat): ?>
                            <tr>
                                <td><?= (int) $cat['id'] ?></td>
                                <td><?= htmlspecialchars($cat['nome']) ?></td>
                                <td><?= htmlspecialchars($cat['descricao'] ?? '') ?></td>
                                <td class="text-end tabela-acoes">
                                    <a href="<?= BASE_URL ?>/categorias/editar.php?id=<?= (int) $cat['id'] ?>"
                                       class="btn btn-sm btn-outline-primary" title="Editar">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <button type="button" class="btn btn-sm btn-outline-danger"
                                            data-bs-toggle="modal"
                                            data-bs-target="#modalExcluir<?= (int) $cat['id'] ?>"
                                            title="Excluir">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

                <?php foreach ($categorias as $cat): ?>
                <div class="modal fade" id="modalExcluir<?= (int) $cat['id'] ?>" tabindex="-1">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Confirmar exclusao</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body">
                                Deseja excluir a categoria
                                <strong><?= htmlspecialchars($cat['nome']) ?></strong>?
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                <a href="<?= BASE_URL ?>/categorias/excluir.php?id=<?= (int) $cat['id'] ?>"
                                   class="btn btn-danger">Excluir</a>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/rodape.php'; ?>
