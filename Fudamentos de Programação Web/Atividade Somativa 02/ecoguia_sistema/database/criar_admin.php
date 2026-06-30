<?php
/**
 * Script auxiliar: cria ou atualiza o usuario admin com senha admin123.
 * Acesse UMA VEZ via navegador: http://localhost/ecoguia_sistema/database/criar_admin.php
 * Depois apague ou renomeie este arquivo por seguranca.
 */
require_once __DIR__ . '/../config/conexao.php';

$email = 'admin@ecoguia.com';
$senha = 'admin123';
$hash  = password_hash($senha, PASSWORD_DEFAULT);

$stmt = $pdo->prepare(
    'INSERT INTO usuarios (nome, email, senha) VALUES (?, ?, ?)
     ON DUPLICATE KEY UPDATE senha = VALUES(senha), nome = VALUES(nome)'
);
$stmt->execute(['Administrador', $email, $hash]);

echo '<h2>Usuario admin configurado!</h2>';
echo '<p>E-mail: <strong>' . htmlspecialchars($email) . '</strong></p>';
echo '<p>Senha: <strong>' . htmlspecialchars($senha) . '</strong></p>';
echo '<p><a href="' . BASE_URL . '/auth/login.php">Ir para o login</a></p>';
