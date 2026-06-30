<?php
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../config/conexao.php';

exigirLogin();

$erro = '';
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
        $verifica = $pdo->prepare('SELECT id FROM categorias WHERE id = ?');
        $verifica->execute([$categoria_id]);

        if (!$verifica->fetch()) {
            $erro = 'Categoria selecionada nao existe.';
        } else {
            $stmt = $pdo->prepare(
                'INSERT INTO projetos (nome, descricao, data_inicio, status, categoria_id)
                 VALUES (?, ?, ?, ?, ?)'
            );
            $stmt->execute([$nome, $descricao, $data_inicio, $status, $categoria_id]);

            header('Location: ' . BASE_URL . '/projetos/listar.php?msg=Projeto+incluido+com+sucesso!&tipo=success');
            exit;
        }
    }
}

$tituloPagina = 'Novo Projeto';
require_once __DIR__ . '/../includes/cabecalho.php';
?>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card card-ecoguia">
                <div class="card-header">
                    <i class="bi bi-plus-lg me-2"></i>Novo projeto
                </div>
                <div class="card-body p-4">
                    <?php if ($erro): ?>
                        <div class="alert alert-danger"><?= htmlspecialchars($erro) ?></div>
                    <?php endif; ?>

                    <?php if (empty($categorias)): ?>
                        <div class="alert alert-warning">
                            Cadastre ao menos uma categoria antes de criar um projeto.
                            <a href="<?= BASE_URL ?>/categorias/inserir.php">Criar categoria</a>
                        </div>
                    <?php else: ?>
                    <form method="post" action="" data-validar novalidate>
                        <div class="mb-3">
                            <label for="nome" class="form-label">Nome do projeto *</label>
                            <input type="text" class="form-control" id="nome" name="nome"
                                   data-regra="obrigatorio|min:3" required maxlength="150"
                                   value="<?= htmlspecialchars($_POST['nome'] ?? '') ?>">
                        </div>
                        <div class="mb-3">
                            <label for="descricao" class="form-label">Descricao</label>
                            <textarea class="form-control" id="descricao" name="descricao" rows="3"><?= htmlspecialchars($_POST['descricao'] ?? '') ?></textarea>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="data_inicio" class="form-label">Data de inicio *</label>
                                <input type="date" class="form-control" id="data_inicio" name="data_inicio"
                                       data-regra="obrigatorio|data" required
                                       value="<?= htmlspecialchars($_POST['data_inicio'] ?? '') ?>">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="status" class="form-label">Status *</label>
                                <select class="form-select" id="status" name="status" required>
                                    <option value="planejado" <?= ($_POST['status'] ?? '') === 'planejado' ? 'selected' : '' ?>>Planejado</option>
                                    <option value="em_andamento" <?= ($_POST['status'] ?? '') === 'em_andamento' ? 'selected' : '' ?>>Em andamento</option>
                                    <option value="concluido" <?= ($_POST['status'] ?? '') === 'concluido' ? 'selected' : '' ?>>Concluido</option>
                                </select>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="categoria_id" class="form-label">Categoria *</label>
                            <select class="form-select" id="categoria_id" name="categoria_id" required>
                                <option value="">Selecione...</option>
                                <?php foreach ($categorias as $cat): ?>
                                <option value="<?= (int) $cat['id'] ?>"
                                    <?= (int) ($_POST['categoria_id'] ?? 0) === (int) $cat['id'] ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($cat['nome']) ?>
                                </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-ecoguia">
                                <i class="bi bi-check-lg me-1"></i>Salvar
                            </button>
                            <a href="<?= BASE_URL ?>/projetos/listar.php" class="btn btn-outline-secondary">Cancelar</a>
                        </div>
                    </form>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/rodape.php'; ?>
