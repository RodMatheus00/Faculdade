<?php
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../config/conexao.php';

redirecionarSeLogado();

$erro = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome  = trim($_POST['nome'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $senha = $_POST['senha'] ?? '';
    $confirmar = $_POST['confirmar_senha'] ?? '';

    if ($nome === '' || $email === '' || $senha === '') {
        $erro = 'Preencha todos os campos obrigatorios.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $erro = 'Informe um e-mail valido.';
    } elseif (strlen($senha) < 6 || !preg_match('/[A-Za-z]/', $senha) || !preg_match('/\d/', $senha)) {
        $erro = 'A senha deve ter no minimo 6 caracteres, com letras e numeros.';
    } elseif ($senha !== $confirmar) {
        $erro = 'As senhas nao conferem.';
    } else {
        $stmt = $pdo->prepare('SELECT id FROM usuarios WHERE email = ? LIMIT 1');
        $stmt->execute([$email]);

        if ($stmt->fetch()) {
            $erro = 'Este e-mail ja esta cadastrado.';
        } else {
            $senhaHash = password_hash($senha, PASSWORD_DEFAULT);

            $insert = $pdo->prepare('INSERT INTO usuarios (nome, email, senha) VALUES (?, ?, ?)');
            $insert->execute([$nome, $email, $senhaHash]);

            header('Location: ' . BASE_URL . '/auth/login.php?cadastro=ok');
            exit;
        }
    }
}

$tituloPagina = 'Cadastro';
require_once __DIR__ . '/../includes/cabecalho.php';
?>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card card-ecoguia">
                <div class="card-header text-center">
                    <i class="bi bi-person-plus me-2"></i>Criar conta no EcoGuia
                </div>
                <div class="card-body p-4">
                    <?php if ($erro): ?>
                        <div class="alert alert-danger" role="alert">
                            <i class="bi bi-exclamation-triangle me-2"></i><?= htmlspecialchars($erro) ?>
                        </div>
                    <?php endif; ?>

                    <form method="post" action="" data-validar novalidate>
                        <div class="mb-3">
                            <label for="nome" class="form-label">Nome completo</label>
                            <input type="text" class="form-control" id="nome" name="nome"
                                   data-regra="obrigatorio|min:3" required minlength="3"
                                   value="<?= htmlspecialchars($_POST['nome'] ?? '') ?>">
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">E-mail</label>
                            <input type="email" class="form-control" id="email" name="email"
                                   data-regra="obrigatorio|email" required
                                   value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">
                        </div>
                        <div class="mb-3">
                            <label for="senha" class="form-label">Senha</label>
                            <input type="password" class="form-control" id="senha" name="senha"
                                   data-regra="obrigatorio|senha" required minlength="6">
                            <div class="form-text">Minimo 6 caracteres, com letras e numeros.</div>
                        </div>
                        <div class="mb-3">
                            <label for="confirmar_senha" class="form-label">Confirmar senha</label>
                            <input type="password" class="form-control" id="confirmar_senha" name="confirmar_senha"
                                   data-regra="obrigatorio|senha" required minlength="6">
                        </div>
                        <button type="submit" class="btn btn-ecoguia w-100">
                            <i class="bi bi-person-check me-2"></i>Cadastrar
                        </button>
                    </form>

                    <p class="text-center text-muted mt-3 mb-0">
                        Ja tem conta?
                        <a href="<?= BASE_URL ?>/auth/login.php">Faca login</a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/rodape.php'; ?>
