<?php
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/config/conexao.php';

exigirLogin();

$totalCategorias = $pdo->query('SELECT COUNT(*) AS total FROM categorias')->fetch()['total'];
$totalProjetos   = $pdo->query('SELECT COUNT(*) AS total FROM projetos')->fetch()['total'];

$projetosRecentes = $pdo->query(
    'SELECT p.id, p.nome, p.status, c.nome AS categoria
     FROM projetos p
     INNER JOIN categorias c ON c.id = p.categoria_id
     ORDER BY p.criado_em DESC
     LIMIT 5'
)->fetchAll();

$tituloPagina = 'Painel';
require_once __DIR__ . '/includes/cabecalho.php';
?>

<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
        <div>
            <h1 class="h3 mb-1">Ola, <?= htmlspecialchars(nomeUsuario()) ?>!</h1>
            <p class="text-muted mb-0">Bem-vindo ao painel de gestao de projetos ambientais.</p>
        </div>
    </div>

    <div class="row g-4 mb-4">
        <div class="col-md-6">
            <div class="card card-ecoguia">
                <div class="card-body d-flex align-items-center gap-3">
                    <i class="bi bi-folder2-open display-5 text-success"></i>
                    <div>
                        <h2 class="h4 mb-0"><?= (int) $totalCategorias ?></h2>
                        <p class="text-muted mb-0">Categorias cadastradas</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card card-ecoguia">
                <div class="card-body d-flex align-items-center gap-3">
                    <i class="bi bi-kanban display-5 text-success"></i>
                    <div>
                        <h2 class="h4 mb-0"><?= (int) $totalProjetos ?></h2>
                        <p class="text-muted mb-0">Projetos cadastrados</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card card-ecoguia mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <span><i class="bi bi-lightning me-2"></i>Acoes rapidas</span>
        </div>
        <div class="card-body">
            <div class="d-flex flex-wrap gap-2">
                <a href="<?= BASE_URL ?>/categorias/listar.php" class="btn btn-ecoguia">
                    <i class="bi bi-folder2-open me-1"></i>Gerenciar categorias
                </a>
                <a href="<?= BASE_URL ?>/projetos/listar.php" class="btn btn-ecoguia">
                    <i class="bi bi-kanban me-1"></i>Gerenciar projetos
                </a>
                <a href="<?= BASE_URL ?>/categorias/inserir.php" class="btn btn-outline-success">
                    <i class="bi bi-plus-lg me-1"></i>Nova categoria
                </a>
                <a href="<?= BASE_URL ?>/projetos/inserir.php" class="btn btn-outline-success">
                    <i class="bi bi-plus-lg me-1"></i>Novo projeto
                </a>
            </div>
        </div>
    </div>

    <div class="card card-ecoguia">
        <div class="card-header">
            <i class="bi bi-clock-history me-2"></i>Projetos recentes
        </div>
        <div class="card-body p-0">
            <?php if (empty($projetosRecentes)): ?>
                <p class="p-4 text-muted mb-0">Nenhum projeto cadastrado ainda.</p>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Projeto</th>
                                <th>Categoria</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($projetosRecentes as $projeto): ?>
                            <tr>
                                <td><?= htmlspecialchars($projeto['nome']) ?></td>
                                <td><?= htmlspecialchars($projeto['categoria']) ?></td>
                                <td>
                                    <span class="badge badge-status-<?= htmlspecialchars($projeto['status']) ?>">
                                        <?= str_replace('_', ' ', htmlspecialchars($projeto['status'])) ?>
                                    </span>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/includes/rodape.php'; ?>
