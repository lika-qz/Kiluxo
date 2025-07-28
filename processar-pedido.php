<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Dados do cliente
    $nome     = htmlspecialchars($_POST['nome'] ?? '');
    $email    = htmlspecialchars($_POST['email'] ?? '');
    $endereco = htmlspecialchars($_POST['endereco'] ?? '');
    $pagamento = htmlspecialchars($_POST['pagamento'] ?? '');

    if (!$nome || !$email || !$endereco || !$pagamento || empty($_SESSION['carrinho'])) {
        echo "Dados incompletos.";
        exit;
    }

    // Monta a mensagem
    $mensagem = "*Novo Pedido Kiluxo 🛍️*\n\n";
    $mensagem .= "*👤 Cliente:*\n";
    $mensagem .= "• Nome: $nome\n";
    $mensagem .= "• Email: $email\n";
    $mensagem .= "• Endereço: $endereco\n";
    $mensagem .= "• Pagamento: " . ucfirst($pagamento) . "\n\n";

    $mensagem .= "*📦 Itens do Pedido:*\n";
    $total = 0;
    foreach ($_SESSION['carrinho'] as $item) {
        $quant = $item['quantidade'];
        $nomeProduto = $item['nome'];
        $preco = number_format($item['preco'], 2, ',', '.');
        $sub = number_format($item['preco'] * $quant, 2, ',', '.');
        $tamanho = $item['tamanho'] ?? '-';
        $cor = $item['cor'] ?? '-';

        $mensagem .= "• $nomeProduto\n";
        $mensagem .= "  - Quantidade: $quant\n";
        $mensagem .= "  - Tamanho: $tamanho\n";
        $mensagem .= "  - Cor: $cor\n";
        $mensagem .= "  - Preço: R$ $preco\n";
        $mensagem .= "  - Subtotal: R$ $sub\n\n";

        $total += $item['preco'] * $quant;
    }

    $mensagem .= "*💰 Total: R$ " . number_format($total, 2, ',', '.') . "*";

  
    $numeroWhatsApp = '559781083160';

    // Encode da mensagem para URL
    $mensagemURL = urlencode($mensagem);
    $link = "https://wa.me/{$numeroWhatsApp}?text={$mensagemURL}";

    // Limpa o carrinho
    unset($_SESSION['carrinho']);

    // Redireciona para o WhatsApp
    header("Location: $link");
    exit;
} else {
    echo "Acesso inválido.";
}
