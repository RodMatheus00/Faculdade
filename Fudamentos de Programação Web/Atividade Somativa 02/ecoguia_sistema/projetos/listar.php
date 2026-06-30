<?php
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../config/conexao.php';

exigirLogin();

$projetos = $pdo->query(
    'SELECT p.*, c.nome AS categoria_nome
     FROM projetos p
     INNER JOIN categorias c ON c.id = p.categoria_id
     ORDER BY p.nome ASC'
)->fetchAll();

$mensagem = $_GET['msg'] ?? '';
$tipoMsg  = $_GET['tipo'] ?? 'success';

$statusLabels = [
    'planejado'     => 'Planejado',
    'em_andamento'  => 'Em andamento',
    'concluido'     => 'Concluido',
];

$tituloPagina = 'Projetos';
require_once __DIR__ . '/../includes/cabecalho.php';
?>

<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
        <h1 class="h3 mb-0"><i class="bi bi-kanban me-2"></i>Projetos</h1>
        <a href="<?= BASE_URL ?>/projetos/inserir.php" class="btn btn-ecoguia">
            <i class="bi bi-plus-lg me-1"></i>Novo projeto
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
            <?php if (empty($projetos)): ?>
                <p class="p-4 text-muted mb-0">Nenhum projeto cadastrado.</p>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-hover mb-0 align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>ID</th>
                                <th>Nome</th>
                                <th>Categoria</th>
                                <th>Data inicio</th>
                                <th>Status</th>
                                <th class="text-end">Acoes</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($projetos as $proj): ?>
                            <tr>
                                <td><?= (int) $proj['id'] ?></td>
                                <td><?= htmlspecialchars($proj['nome']) ?></td>
                                <td><?= htmlspecialchars($proj['categoria_nome']) ?></td>
                                <td><?= date('d/m/Y', strtotime($proj['data_inicio'])) ?></td>
                                <td>
                                    <span class="badge badge-status-<?= htmlspecialchars($proj['status']) ?>">
                                        <?= $statusLabels[$proj['status']] ?? $proj['status'] ?>
                                    </span>
                                </td>
                                <td class="text-end tabela-acoes">
                                    <a href="<?= BASE_URL ?>/projetos/editar.php?id=<?= (int) $proj['id'] ?>"
                                       class="btn btn-sm btn-outline-primary" title="Editar">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <button type="button" class="btn btn-sm btn-outline-danger"
                                            data-bs-toggle="modal"
                                            data-bs-target="#modalExcluirProj<?= (int) $proj['id'] ?>"
                                            title="Excluir">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

                <?php foreach ($projetos as $proj): ?>
                <div class="modal fade" id="modalExcluirProj<?= (int) $proj['id'] ?>" tabindex="-1">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Confirmar exclusao</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body">
                                Deseja excluir o projeto
                                <strong><?= htmlspecialchars($proj['nome']) ?></strong>?
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                <a href="<?= BASE_URL ?>/projetos/excluir.php?id=<?= (int) $proj['id'] ?>"
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
