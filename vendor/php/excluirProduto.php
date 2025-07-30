<?php
session_start();
if (!isset($_SESSION['usuario_logado'])) {
    header('Location: ../../index.php');
    exit;
}

require_once "conexao.php";

// Verifica se o ID foi passado corretamente
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = (int)$_GET['id'];

    try {
        // Verifica se o produto existe e busca o nome da imagem
        $stmt = $pdo->prepare("SELECT imagem FROM produtos WHERE id = ?");
        $stmt->execute([$id]);

        if ($stmt->rowCount() > 0) {
            $produto = $stmt->fetch();

            // Exclui a imagem do servidor (caso exista)
            if (!empty($produto['imagem']) && file_exists("../uploads/" . $produto['imagem'])) {
                unlink("../uploads/" . $produto['imagem']);
            }

            // Exclui o produto do banco
            $delete = $pdo->prepare("DELETE FROM produtos WHERE id = ?");
            $delete->execute([$id]);

            header("Location: ../../adm/produtos.php?excluido=1");
            exit;
        } else {
            header("Location: ../../adm/produtos.php?erro=nao_encontrado");
            exit;
        }
    } catch (PDOException $e) {
        die("Erro ao excluir produto: " . $e->getMessage());
    }
} else {
    header("Location: ../../adm/produtos.php?erro=id_invalido");
    exit;
}
