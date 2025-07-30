<?php
session_start();
if (!isset($_SESSION['usuario_logado'])) {
    header('Location: index.php');
    exit;
}

require_once '../vendor/php/conexao.php';

// Total de vendas hoje
$stmtHoje = $pdo->prepare("SELECT SUM(total) AS total_hoje FROM pedidos WHERE DATE(criado_em) = CURDATE() AND status = 'Pago'");
$stmtHoje->execute();
$totalHoje = $stmtHoje->fetchColumn() ?? 0;

// Total de pedidos
$stmtPedidos = $pdo->query("SELECT COUNT(*) FROM pedidos");
$totalPedidos = $stmtPedidos->fetchColumn() ?? 0;

// Lucro do mês
$stmtMes = $pdo->prepare("SELECT SUM(total) FROM pedidos WHERE MONTH(criado_em) = MONTH(CURRENT_DATE()) AND YEAR(criado_em) = YEAR(CURRENT_DATE()) AND status = 'Pago'");
$stmtMes->execute();
$lucroMes = $stmtMes->fetchColumn() ?? 0;

// Lucro da semana (últimos 7 dias)
$stmtSemana = $pdo->prepare("SELECT SUM(total) FROM pedidos WHERE criado_em >= DATE_SUB(CURDATE(), INTERVAL 7 DAY) AND status = 'Pago'");
$stmtSemana->execute();
$lucroSemana = $stmtSemana->fetchColumn() ?? 0;

// Últimos pedidos
$stmtRecentes = $pdo->query("SELECT * FROM pedidos ORDER BY criado_em DESC LIMIT 5");
$pedidosRecentes = $stmtRecentes->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Painel Admin | Kiluxo</title>

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
            <li class="nav-item">
                <a class="nav-link active" href="#">
                    <i class="bi bi-speedometer2"></i>
                    <span>Dashboard</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="vendas.php">
                    <i class="bi bi-cart4"></i>
                    <span>Vendas</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="produtos.php">
                    <i class="bi bi-box-seam"></i>
                    <span>Produtos</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="usuarios.php">
                    <i class="bi bi-people"></i>
                    <span>Usuários</span>
                </a>
            </li>

            <div class="divider"></div>


            <li class="nav-item">
                <a class="nav-link" href="../vendor/php/logout.php">
                    <i class="bi bi-box-arrow-right"></i>
                    <span>Sair</span>
                </a>
            </li>
        </ul>
    </div>

    <!-- Main Content -->
    <div class="main-content mt-5">

        <!-- Stats Cards -->
        <div class="row g-3 mb-4">
            <!-- Vendas Hoje -->
            <div class="col-6 col-md-3">
                <div class="stat-card d-flex align-items-center p-3">
                    <div class="icon-container icon-sales me-3">
                        <i class="bi bi-currency-dollar"></i>
                    </div>
                    <div>
                        <div class="stat-value">R$ <?= number_format($totalHoje, 2, ',', '.') ?></div>
                        <div class="stat-label">Vendas Hoje</div>

                    </div>
                </div>
            </div>

            <!-- Pedidos -->
            <div class="col-6 col-md-3">
                <div class="stat-card d-flex align-items-center p-3">
                    <div class="icon-container icon-orders me-3">
                        <i class="bi bi-cart-check"></i>
                    </div>
                    <div>
                        <div class="stat-value"><?= $totalPedidos ?></div>
                        <div class="stat-label">Pedidos</div>

                    </div>
                </div>
            </div>

            <!-- Lucro do Mês -->
            <div class="col-6 col-md-3">
                <div class="stat-card d-flex align-items-center p-3">
                    <div class="icon-container icon-users me-3">
                        <i class="bi bi-graph-up-arrow"></i>
                    </div>
                    <div>
                        <div class="stat-value">R$ <?= number_format($lucroMes, 2, ',', '.') ?></div>
                        <div class="stat-label">Lucro do Mês</div>

                    </div>
                </div>
            </div>

            <!-- Reembolsos -->
            <div class="col-6 col-md-3">
                <div class="stat-card d-flex align-items-center p-3">
                    <div class="icon-container icon-satisfaction me-3">
                        <i class="bi-cash-stack"></i>
                    </div>
                    <div>
                        <div class="stat-value">R$ <?= number_format($lucroSemana, 2, ',', '.') ?></div>
                        <div class="stat-label">Lucro da Semana</div>

                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Orders -->
        <div class="data-table">
            <div class="table-header">
                <h3 class="table-title">Pedidos Recentes</h3>
                <a href="vendas.php" class="btn btn-sm btn-outline-primary">Ver todos</a>
            </div>

            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead>
                        <tr>
                            <th># Pedido</th>
                            <th>Cliente</th>
                            <th>Data</th>
                            <th>Valor</th>
                            <th>Status</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($pedidosRecentes as $pedido): ?>
                            <tr>
                                <td>#KLX-<?= str_pad($pedido['id'], 4, '0', STR_PAD_LEFT) ?></td>
                                <td><?= htmlspecialchars($pedido['nome_cliente']) ?></td>
                                <td><?= date('d/m/Y', strtotime($pedido['criado_em'])) ?></td>
                                <td>R$ <?= number_format($pedido['total'], 2, ',', '.') ?></td>
                                <td>
                                    <span class="badge 
                <?= $pedido['status'] === 'Pago' ? 'bg-success' :
                    ($pedido['status'] === 'Pendente' ? 'bg-warning' :
                        ($pedido['status'] === 'Cancelado' ? 'bg-danger' : 'bg-secondary')) ?>">
                                        <?= htmlspecialchars($pedido['status']) ?>
                                    </span>
                                </td>
                                <td>
                                    <a href="vendas.php#pedido-<?= $pedido['id'] ?>" class="btn btn-sm btn-outline-primary">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>

                </table>
            </div>
        </div>

        <!-- Activity Feed -->

    </div>

    <!-- Bootstrap JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>


</body>

</html>