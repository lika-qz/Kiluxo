<?php
require_once "conexao.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $id = intval($_POST['id']);
    $nome = trim($_POST['nome']);
    $categoria = trim($_POST['categoria']);
    $cor = trim($_POST['cor']);
    $preco = floatval($_POST['preco']);
    $estoque = intval($_POST['estoque']);
    $descricao = trim($_POST['descricao']);
    $status = $estoque > 0 ? 'disponivel' : 'indisponivel';
   
    // Atualiza produto
    $sql = "UPDATE produtos SET nome = ?, categoria = ?, cor = ?, preco = ?, estoque = ?, status = ?, descricao = ? WHERE id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$nome, $categoria, $cor, $preco, $estoque, $status, $descricao, $id]);

    header("Location: ../../adm/produtos.php?atualizado=1");
    exit;
} else {
    echo "Requisição inválida.";
    exit;
}
