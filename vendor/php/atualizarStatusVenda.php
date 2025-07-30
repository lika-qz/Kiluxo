<?php
session_start();
require_once 'conexao.php';

if (!isset($_SESSION['usuario_logado'])) {
    header('Location: ../../admin/index.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $pedidoId = $_POST['venda_id'] ?? null;
    $novoStatus = $_POST['status'] ?? null;

    if (!$pedidoId || !$novoStatus) {
        die('Dados inválidos');
    }

    try {
        // Atualizar status no banco
        $stmt = $pdo->prepare("UPDATE pedidos SET status = :status WHERE id = :id");
        $stmt->execute([
            ':status' => $novoStatus,
            ':id'     => $pedidoId
        ]);

        // Confirmar se algo foi atualizado
        if ($stmt->rowCount() === 0) {
            die("Nenhum pedido atualizado. Verifique o ID.");
        }

        // Buscar dados do cliente
        $stmt = $pdo->prepare("SELECT nome_cliente, telefone FROM pedidos WHERE id = :id");
        $stmt->execute([':id' => $pedidoId]);
        $pedido = $stmt->fetch();

        if (!$pedido || empty($pedido['telefone'])) {
            header('Location: ../../adm/vendas.php?erro=telefone');
            exit;
        }

        // Preparar WhatsApp
        $telefone = preg_replace('/\D/', '', $pedido['telefone']); // remove tudo que não é número
        $mensagem = "Olá, " . $pedido['nome_cliente'] . "! ";

        // Gerar mensagem personalizada conforme o status
        switch ($novoStatus) {
            case 'Pago':
                $mensagem .= "Recebemos o pagamento do seu pedido. Obrigado pela compra! Em breve será embalado. 💳📦";
                break;
            case 'Pendente':
                $mensagem .= "Seu pedido está pendente. Assim que recebermos o pagamento, daremos andamento. ⏳";
                break;
            case 'Cancelado':
                $mensagem .= "Seu pedido foi cancelado. Se tiver dúvidas, entre em contato conosco. ❌";
                break;
            case 'Embalando':
                $mensagem .= "Seu pedido está sendo embalado e logo será enviado! 🎁📦";
                break;
            case 'Enviado':
                $mensagem .= "Seu pedido foi enviado! Fique de olho na entrega. 🚚📦";
                break;
            case 'Entregue':
                $mensagem .= "Seu pedido foi entregue! Agradecemos pela confiança. 🙌";
                break;
            default:
                $mensagem .= "O status do seu pedido foi atualizado para: $novoStatus.";
        }

        // Redirecionar para WhatsApp
        $urlWhatsapp = "https://wa.me/55$telefone?text=" . urlencode($mensagem);
        header("Location: $urlWhatsapp");
        exit;

    } catch (PDOException $e) {
        error_log("Erro ao atualizar status: " . $e->getMessage());
        die("Erro ao atualizar status.");
    }
}
?>
