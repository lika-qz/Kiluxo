<?php
session_start();
require_once 'conexao.php';

// Ativar exibição de erros para depuração
ini_set('display_errors', 1);
error_reporting(E_ALL);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

if (empty($_SESSION['carrinho'])) {
    die('Carrinho vazio');
}

// Calcular total do carrinho
$total = 0;
foreach ($_SESSION['carrinho'] as $item) {
    $total += $item['preco'] * $item['quantidade'];
}

try {
    $pdo->beginTransaction();

    // Inserir pedido
    $stmt = $pdo->prepare("
        INSERT INTO pedidos 
            (nome_cliente, email, endereco, metodo_pagamento, total)
        VALUES 
            (:nome, :email, :endereco, :pagamento, :total)
    ");
    $stmt->execute([
        ':nome'       => $_POST['nome'],
        ':email'      => $_POST['email'],
        ':endereco'   => $_POST['endereco'],
        ':pagamento'  => $_POST['pagamento'],
        ':total'      => $total
    ]);

    if ($stmt->rowCount() === 0) {
        throw new Exception("Pedido não inserido");
    }

    $pedidoId = $pdo->lastInsertId();
    if (!$pedidoId) {
        throw new Exception("ID do pedido inválido");
    }

    // Preparar inserção de itens e atualização de estoque
    $stmtItem = $pdo->prepare("
        INSERT INTO itens_pedido
            (pedido_id, produto_id, tamanho, cor, preco_unitario, quantidade, subtotal)
        VALUES
            (:pedido_id, :produto_id, :tamanho, :cor, :preco, :quantidade, :subtotal)
    ");
    $stmtUpdate = $pdo->prepare("
        UPDATE produtos 
        SET estoque = estoque - :quantidade 
        WHERE id = :produto_id
    ");

    // Inserir itens e atualizar estoque
    foreach ($_SESSION['carrinho'] as $item) {
        $subtotal = $item['preco'] * $item['quantidade'];
        $stmtItem->execute([
            ':pedido_id'   => $pedidoId,
            ':produto_id'  => $item['id'],
            ':tamanho'     => $item['tamanho'],
            ':cor'         => $item['cor'],
            ':preco'       => $item['preco'],
            ':quantidade'  => $item['quantidade'],
            ':subtotal'    => $subtotal
        ]);

        if ($stmtItem->rowCount() === 0) {
            throw new Exception("Item não inserido: produto_id={$item['id']}");
        }

        $stmtUpdate->execute([
            ':quantidade'  => $item['quantidade'],
            ':produto_id'  => $item['id']
        ]);

        if ($stmtUpdate->rowCount() === 0) {
            throw new Exception("Falha ao atualizar estoque: produto_id={$item['id']}");
        }
    }

    $pdo->commit();

    // Montar mensagem para WhatsApp
    $numeroLoja = '5597981083160'; // código internacional +55 Brasil
    $mensagem = "Olá! Fiz uma compra na loja Kiluxo:\n\n";
    $mensagem .= "Nome: " . $_POST['nome'] . "\n";
    $mensagem .= "Email: " . $_POST['email'] . "\n";
    $mensagem .= "Endereço: " . $_POST['endereco'] . "\n";
    $mensagem .= "Pagamento: " . $_POST['pagamento'] . "\n\n";
    $mensagem .= "Itens do pedido:\n";

    foreach ($_SESSION['carrinho'] as $item) {
        $mensagem .= "- " . $item['nome'] .
            " x" . $item['quantidade'] .
            " (R$ " . number_format($item['preco'], 2, ',', '.') . ")\n";
    }

    $mensagem .= "\nTotal: R$ " . number_format($total, 2, ',', '.');

    // Limpar carrinho
    unset($_SESSION['carrinho']);

    // Redirecionar para enviar mensagem no WhatsApp
    $urlWhatsapp = "https://wa.me/{$numeroLoja}?text=" . urlencode($mensagem);
    header("Location: $urlWhatsapp");
    exit;

} catch (Exception $e) {
    $pdo->rollBack();
    error_log('Erro ao processar pedido: ' . $e->getMessage());
    die('Erro ao processar pedido. Tente novamente mais tarde.');
}
?>
