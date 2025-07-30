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
                    <tbody>
                        <tr>
                            <td>#VND-2025</td>
                            <td>Lucas Andrade</td>
                            <td>30/07/2025</td>
                            <td>Notebook, Teclado</td>
                            <td>R$ 3.200,00</td>
                            <td><span class="badge bg-success">Pago</span></td>
                            <td>
                                <button class="btn btn-sm btn-outline-secondary editar-status-btn"
                                    data-bs-toggle="modal" data-bs-target="#modalStatusVenda" data-venda-id="VND-2025"
                                    data-status-atual="Pago">
                                    <i class="bi bi-pencil"></i>
                                </button>
                            </td>

                        </tr>
                        <!-- Adicione mais linhas conforme necessário -->
                    </tbody>
                </table>

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
    document.querySelectorAll('.editar-status-btn').forEach(button => {
        button.addEventListener('click', () => {
            const vendaId = button.getAttribute('data-venda-id');
            const statusAtual = button.getAttribute('data-status-atual');

            document.getElementById('inputVendaId').value = vendaId;
            document.getElementById('selectNovoStatus').value = statusAtual;
        });
    });
</script>

</html>