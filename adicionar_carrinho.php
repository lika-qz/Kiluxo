<?php
session_start();

// Inicializa o carrinho se não existir
if (!isset($_SESSION['carrinho'])) {
    $_SESSION['carrinho'] = [];
}

// Recebe dados do produto via POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = $_POST['nome'] ?? '';
    $preco = $_POST['preco'] ?? '';
    $imagem = $_POST['imagem'] ?? '';
    $descricao = $_POST['descricao'] ?? '';
    $quantidade = $_POST['quantidade'] ?? 1;

    if ($nome && $preco && $imagem) {
        // Adiciona ao carrinho
        $_SESSION['carrinho'][] = [
            'nome' => $nome,
            'preco' => $preco,
            'imagem' => $imagem,
            'descricao' => $descricao,
            'quantidade' => $quantidade
        ];
        header('Location: shoping-cart.php');
        exit;
    }
}

// Redireciona se acesso direto
header('Location: product.html');
exit;
