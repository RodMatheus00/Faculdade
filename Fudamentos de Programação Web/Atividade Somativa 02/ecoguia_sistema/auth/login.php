<?php
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../config/conexao.php';

redirecionarSeLogado();

$erro = '';
$sucesso = '';

if (isset($_GET['erro']) && $_GET['erro'] === 'acesso_negado') {
    $erro = 'Voce precisa estar autenticado para acessar esta pagina.';
}

if (isset($_GET['logout']) && $_GET['logout'] === 'ok') {
    $sucesso = 'Logout realizado com sucesso.';
}

if (isset($_GET['cadastro']) && $_GET['cadastro'] === 'ok') {
    $sucesso = 'Cadastro realizado! Faca login para continuar.';
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $senha = $_POST['senha'] ?? '';

    if ($email === '' || $senha === '') {
        $erro = 'Preencha e-mail e senha.';
    } else {
        $stmt = $pdo->prepare('SELECT id, nome, email, senha FROM usuarios WHERE email = ? LIMIT 1');
        $stmt->execute([$email]);
        $usuario = $stmt->fetch();

        if ($usuario && password_verify($senha, $usuario['senha'])) {
            fazerLogin($usuario);
            header('Location: ' . BASE_URL . '/dashboard.php');
            exit;
        }

        $erro = 'E-mail ou senha invalidos.';
    }
}

$tituloPagina = 'Login';
require_once __DIR__ . '/../includes/cabecalho.php';
?>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card card-ecoguia">
                <div class="card-header text-center">
                    <i class="bi bi-box-arrow-in-right me-2"></i>Entrar no EcoGuia
                </div>
                <div class="card-body p-4">
                    <?php if ($erro): ?>
                        <div class="alert alert-danger" role="alert">
                            <i class="bi bi-exclamation-triangle me-2"></i><?= htmlspecialchars($erro) ?>
                        </div>
                    <?php endif; ?>

                    <?php if ($sucesso): ?>
                        <div class="alert alert-success" role="alert">
                            <i class="bi bi-check-circle me-2"></i><?= htmlspecialchars($sucesso) ?>
                        </div>
                    <?php endif; ?>

                    <form method="post" action="" data-validar novalidate>
                        <div class="mb-3">
                            <label for="email" class="form-label">E-mail</label>
                            <input type="email" class="form-control" id="email" name="email"
                                   data-regra="obrigatorio|email" required
                                   value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">
                        </div>
                        <div class="mb-3">
                            <label for="senha" class="form-label">Senha</label>
                            <input type="password" class="form-control" id="senha" name="senha"
                                   data-regra="obrigatorio|min:6" required minlength="6">
                        </div>
                        <button type="submit" class="btn btn-ecoguia w-100">
                            <i class="bi bi-box-arrow-in-right me-2"></i>Entrar
                        </button>
                    </form>

                    <p class="text-center text-muted mt-3 mb-0">
                        Nao tem conta?
                        <a href="<?= BASE_URL ?>/auth/cadastro.php">Cadastre-se</a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/rodape.php'; ?>
