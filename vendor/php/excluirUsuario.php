<?php
session_start();
if (!isset($_SESSION['usuario_logado'])) {
    header('Location: ../index.php');
    exit;
}

require_once "conexao.php";

// Verifica se foi passado um ID válido
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = (int)$_GET['id'];

    try {
        // Verifica se o usuário existe antes de excluir
        $check = $pdo->prepare("SELECT * FROM usuarios WHERE id = ?");
        $check->execute([$id]);

        if ($check->rowCount() > 0) {
            // Executa a exclusão
            $stmt = $pdo->prepare("DELETE FROM usuarios WHERE id = ?");
            $stmt->execute([$id]);

            // Redireciona de volta para a listagem com mensagem de sucesso
            header("Location: ../../adm/usuarios.php?excluido=1");
            exit;
        } else {
            header("Location: ../../adm/usuarios.php?erro=nao_encontrado");
            exit;
        }
    } catch (PDOException $e) {
        die("Erro ao excluir: " . $e->getMessage());
    }
} else {
    header("Location: ../../adm/usuarios.php?erro=id_invalido");
    exit;
}
