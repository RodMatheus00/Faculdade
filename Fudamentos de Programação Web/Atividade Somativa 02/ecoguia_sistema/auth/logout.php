<?php
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../config/conexao.php';

fazerLogout();

header('Location: ' . BASE_URL . '/auth/login.php?logout=ok');
exit;
