<?php
session_start();
if (!isset($_SESSION['usuario_logado'])) {
    header('Location: index.php');
    exit;
}

?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Vendas | Kiluxo</title>

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.3/font/bootstrap-icons.css">
    <link rel="stylesheet" href="../css/adm.css">

</head>

<body>
    <!-- Navbar -->
    <nav class="navbar navbar-main navbar-expand-lg fixed-top">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">
                <img src="../images/logo.png" class="navbar-logo">
            </a>
            <div class="d-flex align-items-center">
                <?php
                $nomeCompleto = $_SESSION['usuario_logado']['nome'];
                $partes = explode(' ', trim($nomeCompleto));
                $primeiro = $partes[0];
                $ultimo = end($partes);
                $nomeExibido = $primeiro . ' ' . $ultimo;
                ?>
                <div class="user-info me-3">
                    <?= htmlspecialchars($nomeExibido) ?>
                </div>
            </div>
        </div>
    </nav>

    <!-- Sidebar -->
    <div class="sidebar">
        <ul class="nav flex-column">
            <li class="nav-item"><a class="nav-link" href="dashboard.php"><i
                        class="bi bi-speedometer2"></i><span>Dashboard</span></a></li>
            <li class="nav-item"><a class="nav-link active" href="vendas.php"><i
                        class="bi bi-cart4"></i><span>Vendas</span></a></li>
            <li class="nav-item"><a class="nav-link" href="produtos.php"><i
                        class="bi bi-box-seam"></i><span>Produtos</span></a></li>
            <li class="nav-item"><a class="nav-link" href="usuarios.php"><i
                        class="bi bi-people"></i><span>Usuários</span></a></li>
            <div class="divider"></div>
            <li class="nav-item"><a class="nav-link" href="../vendor/php/logout.php"><i
                        class="bi bi-box-arrow-right"></i><span>Sair</span></a></li>
        </ul>
    </div>

    <!-- Main Content -->
    <div class="main-content mt-5">
        <div class="data-table">
            <div class="table-header d-flex justify-content-between align-items-center">
                <h3 class="table-title">Histórico de Vendas</h3>

            </div>

            <div class="table-responsive">
                <table class="table table-striped align-middle">
                    <thead>
                        <tr>
                            <th># Venda</th>
                            <th>Cliente</th>
                            <th>Data</th>
                            <th>Produtos</th>
                            <th>Total</th>
                            <th>Status</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <?php


                    require_once '../vendor/php/conexao.php';

                    $stmt = $pdo->query("SELECT * FROM pedidos ORDER BY criado_em DESC");
                    $pedidos = $stmt->fetchAll();

                    foreach ($pedidos as $pedido):
                        // Buscar produtos desse pedido
                        $stmtItens = $pdo->prepare("
                            SELECT ip.*, p.nome 
                            FROM itens_pedido ip
                            JOIN produtos p ON p.id = ip.produto_id
                            WHERE ip.pedido_id = ?
                        ");
                        $stmtItens->execute([$pedido['id']]);
                        $itens = $stmtItens->fetchAll();

                        $nomesProdutos = array_map(function ($item) {
                            return htmlspecialchars($item['nome']) . " x" . $item['quantidade'];
                        }, $itens);

                        $produtosTexto = implode(', ', $nomesProdutos);

                        // Badge do status
                        $badgeClass = match ($pedido['status']) {
                            'Pago' => 'success',
                            'Pendente' => 'warning',
                            'Cancelado' => 'danger',
                            'Embalando' => 'info',
                            'Enviado' => 'primary',
                            'Entregue' => 'dark',
                            default => 'secondary',
                        };
                        ?>
                        <tbody>
                            <tr>
                                <td>#VND-<?= str_pad($pedido['id'], 4, '0', STR_PAD_LEFT) ?></td>
                                <td><?= htmlspecialchars($pedido['nome_cliente']) ?></td>
                                <td><?= date('d/m/Y', strtotime($pedido['criado_em'])) ?></td>
                                <td><?= $produtosTexto ?></td>
                                <td>R$ <?= number_format($pedido['total'], 2, ',', '.') ?></td>
                                <td><span class="badge bg-<?= $badgeClass ?>"><?= $pedido['status'] ?></span></td>
                                <td class="d-flex gap-1">
                                    <!-- Botão editar status -->
                                    <button class="btn btn-sm btn-outline-secondary editar-status-btn"
                                        data-bs-toggle="modal" data-bs-target="#modalStatusVenda"
                                        data-venda-id="<?= $pedido['id'] ?>" data-status-atual="<?= $pedido['status'] ?>">
                                        <i class="bi bi-pencil"></i>
                                    </button>

                                    <!-- Botão visualizar cliente -->
                                    <button class="btn btn-sm btn-outline-primary visualizar-cliente-btn"
                                        data-bs-toggle="modal" data-bs-target="#modalVisualizarCliente"
                                        data-nome="<?= htmlspecialchars($pedido['nome_cliente']) ?>"
                                        data-email="<?= htmlspecialchars($pedido['email']) ?>"
                                        data-telefone="<?= htmlspecialchars($pedido['telefone']) ?>"
                                        data-endereco="<?= htmlspecialchars($pedido['endereco']) ?>">
                                        <i class="bi bi-person-lines-fill"></i>
                                    </button>
                                </td>

                            </tr>
                        <?php endforeach; ?>
                        <!-- Adicione mais linhas conforme necessário -->
                    </tbody>
                </table>

            </div>
        </div>
    </div>
    <div class="modal fade" id="modalVisualizarCliente" tabindex="-1" aria-labelledby="modalVisualizarClienteLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Dados do Cliente</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
                </div>
                <div class="modal-body">
                    <p><strong>Nome:</strong> <span id="clienteNome"></span></p>
                    <p><strong>Email:</strong> <span id="clienteEmail"></span></p>
                    <p><strong>Endereço:</strong> <span id="clienteEndereco"></span></p>
                    <p><strong>Telefone:</strong> <a href="#" id="clienteWhatsapp" target="_blank"
                            class="btn btn-success btn-sm">
                            <i class="bi bi-whatsapp"></i> Enviar mensagem
                        </a></p>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                </div>
            </div>
        </div>
    </div>


    <div class="modal fade" id="modalStatusVenda" tabindex="-1" aria-labelledby="modalStatusVendaLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <form id="formAtualizarStatus" method="post" action="../vendor/php/atualizarStatusVenda.php">

                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalStatusVendaLabel">Alterar Status da Venda</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="venda_id" id="inputVendaId">

                        <div class="mb-3">
                            <label for="status" class="form-label">Novo Status</label>
                            <select name="status" id="selectNovoStatus" class="form-select" required>
                                <option value="Pago">Pago</option>
                                <option value="Pendente">Pendente</option>
                                <option value="Cancelado">Cancelado</option>
                                <option value="Embalando">Embalando</option>
                                <option value="Enviado">Enviado</option>
                                <option value="Entregue">Entregue</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success">Salvar</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    </div>
                </div>
            </form>
        </div>
    </div>


    <!-- Bootstrap Bundle JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
<script>
    document.querySelectorAll('.visualizar-cliente-btn').forEach(button => {
        button.addEventListener('click', () => {
            document.getElementById('clienteNome').textContent = button.getAttribute('data-nome');
            document.getElementById('clienteEmail').textContent = button.getAttribute('data-email');
            document.getElementById('clienteEndereco').textContent = button.getAttribute('data-endereco');

            const telefone = button.getAttribute('data-telefone').replace(/\D/g, '');
            const linkWhatsapp = `https://wa.me/55${telefone}`;
            document.getElementById('clienteWhatsapp').href = linkWhatsapp;
        });
    });
</script>

<script>
    // Modal de editar status
    document.querySelectorAll('.editar-status-btn').forEach(button => {
        button.addEventListener('click', () => {
            const vendaId = button.getAttribute('data-venda-id');
            const statusAtual = button.getAttribute('data-status-atual');

            document.getElementById('inputVendaId').value = vendaId;
            document.getElementById('selectNovoStatus').value = statusAtual;
        });
    });

    // Modal de visualizar cliente
    document.querySelectorAll('.visualizar-cliente-btn').forEach(button => {
        button.addEventListener('click', () => {
            document.getElementById('clienteNome').textContent = button.getAttribute('data-nome');
            document.getElementById('clienteEmail').textContent = button.getAttribute('data-email');
            document.getElementById('clienteEndereco').textContent = button.getAttribute('data-endereco');
        });
    });
</script>


</html>