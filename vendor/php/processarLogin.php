<?php
session_start();
require 'conexao.php';

$email = $_POST['email'] ?? '';
$senha = $_POST['senha'] ?? '';

if (empty($email) || empty($senha)) {
    $_SESSION['erro_login'] = 'Preencha todos os campos.';
    header('Location: ../../adm/index.php');
    exit;
}

try {
    $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE email = :email LIMIT 1");
    $stmt->bindParam(':email', $email, PDO::PARAM_STR);
    $stmt->execute();
    $usuario = $stmt->fetch();

    if ($usuario && password_verify($senha, $usuario['senha'])) {
        $_SESSION['usuario_logado'] = [
            'id' => $usuario['id'],
            'nome' => $usuario['nome'],
            'email' => $usuario['email']
        ];
        header('Location: ../../adm/dashboard.php');
        exit;
    } else {
        $_SESSION['erro_login'] = 'Email ou senha inválidos.';
        header('Location: ../../adm/index.php');
        exit;
    }
} catch (PDOException $e) {
    $_SESSION['erro_login'] = 'Erro no login: ' . $e->getMessage();
    header('Location: ../../adm/index.php');
    exit;
}
