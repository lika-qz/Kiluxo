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
                <img src="../images/logo.png"  class="navbar-logo">
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
                <a class="nav-link" href="#">
                    <i class="bi bi-cart4"></i>
                    <span>Vendas</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#">
                    <i class="bi bi-box-seam"></i>
                    <span>Produtos</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#">
                    <i class="bi bi-people"></i>
                    <span>Usuários</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#">
                    <i class="bi bi-gear"></i>
                    <span>Configurações</span>
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
                        <div class="stat-value">R$ 8.245</div>
                        <div class="stat-label">Vendas Hoje</div>
                        <div class="stat-change change-up">
                            <i class="bi bi-arrow-up"></i> 12.4% desde ontem
                        </div>
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
                        <div class="stat-value">187</div>
                        <div class="stat-label">Pedidos</div>
                        <div class="stat-change change-up">
                            <i class="bi bi-arrow-up"></i> 5.2% desde ontem
                        </div>
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
                        <div class="stat-value">R$ 23.150</div>
                        <div class="stat-label">Lucro do Mês</div>
                        <div class="stat-change change-up">
                            <i class="bi bi-arrow-up"></i> 8.6% este mês
                        </div>
                    </div>
                </div>
            </div>

            <!-- Reembolsos -->
            <div class="col-6 col-md-3">
                <div class="stat-card d-flex align-items-center p-3">
                    <div class="icon-container icon-satisfaction me-3">
                        <i class="bi bi-arrow-counterclockwise"></i>
                    </div>
                    <div>
                        <div class="stat-value">R$ 1.245</div>
                        <div class="stat-label">Reembolsos</div>
                        <div class="stat-change change-down">
                            <i class="bi bi-arrow-down"></i> 1.3% este mês
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Orders -->
        <div class="data-table">
            <div class="table-header">
                <h3 class="table-title">Pedidos Recentes</h3>
                <a href="#" class="btn btn-sm btn-outline-primary">Ver todos</a>
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
                        <tr>
                            <td>#KLX-7842</td>
                            <td>Marcos Silva</td>
                            <td>30/07/2025</td>
                            <td>R$ 245,90</td>
                            <td><span class="badge badge-completed">Completo</span></td>
                            <td>
                                <button class="btn btn-sm btn-outline-primary">
                                    <i class="bi bi-eye"></i>
                                </button>
                            </td>
                        </tr>
                        <tr>
                            <td>#KLX-7841</td>
                            <td>Ana Costa</td>
                            <td>30/07/2025</td>
                            <td>R$ 128,50</td>
                            <td><span class="badge badge-completed">Completo</span></td>
                            <td>
                                <button class="btn btn-sm btn-outline-primary">
                                    <i class="bi bi-eye"></i>
                                </button>
                            </td>
                        </tr>
                        <tr>
                            <td>#KLX-7840</td>
                            <td>Carlos Oliveira</td>
                            <td>29/07/2025</td>
                            <td>R$ 89,90</td>
                            <td><span class="badge badge-completed">Completo</span></td>
                            <td>
                                <button class="btn btn-sm btn-outline-primary">
                                    <i class="bi bi-eye"></i>
                                </button>
                            </td>
                        </tr>
                        <tr>
                            <td>#KLX-7839</td>
                            <td>Juliana Mendes</td>
                            <td>29/07/2025</td>
                            <td>R$ 342,20</td>
                            <td><span class="badge badge-pending">Pendente</span></td>
                            <td>
                                <button class="btn btn-sm btn-outline-primary">
                                    <i class="bi bi-eye"></i>
                                </button>
                            </td>
                        </tr>
                        <tr>
                            <td>#KLX-7838</td>
                            <td>Ricardo Almeida</td>
                            <td>28/07/2025</td>
                            <td>R$ 156,75</td>
                            <td><span class="badge badge-completed">Completo</span></td>
                            <td>
                                <button class="btn btn-sm btn-outline-primary">
                                    <i class="bi bi-eye"></i>
                                </button>
                            </td>
                        </tr>
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