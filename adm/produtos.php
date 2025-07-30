<?php
session_start();
if (!isset($_SESSION['usuario_logado'])) {
    header('Location: index.php');
    exit;
}

require_once "../vendor/php/conexao.php";

try {
    $stmt = $pdo->query("SELECT * FROM produtos ORDER BY criado_em DESC");
    $produtos = $stmt->fetchAll();
} catch (PDOException $e) {
    echo "<div class='alert alert-danger'>Erro ao carregar produtos: " . $e->getMessage() . "</div>";
}
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Produtos | Kiluxo</title>

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
            <li class="nav-item"><a class="nav-link" href="vendas.php"><i
                        class="bi bi-cart4"></i><span>Vendas</span></a></li>
            <li class="nav-item"><a class="nav-link active" href="produtos.php"><i
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
                <h3 class="table-title">Gerenciamento de Produtos</h3>
                <a href="cadastrarProduto.php" class="btn btn-sm btn-outline-success"><i class="bi bi-plus-circle"></i>
                    Novo Produto</a>
            </div>

            <div class="table-responsive">
                <table class="table table-striped align-middle">
                    <thead>
                        <tr>
                            <th>#ID</th>
                            <th>Nome</th>
                            <th>Categoria</th>
                            <th>Preço</th>
                            <th>Estoque</th>
                            <th>Status</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($produtos)): ?>
                            <?php foreach ($produtos as $produto): ?>
                                <tr>
                                    <td><?= htmlspecialchars($produto['id']) ?></td>
                                    <td><?= htmlspecialchars($produto['nome']) ?></td>
                                    <td><?= htmlspecialchars($produto['categoria']) ?></td>
                                    <td>R$ <?= number_format($produto['preco'], 2, ',', '.') ?></td>
                                    <td><?= $produto['estoque'] ?></td>
                                    <td>
                                        <span
                                            class="badge <?= $produto['status'] === 'disponivel' ? 'bg-success' : 'bg-danger' ?>">
                                            <?= ucfirst($produto['status']) ?>
                                        </span>
                                    </td>
                                    <td>
                                        <a href="editarProduto.php?id=<?= $produto['id'] ?>"
                                            class="btn btn-sm btn-outline-primary">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <a href="../vendor/php/excluirProduto.php?id=<?= $produto['id'] ?>"
                                            class="btn btn-sm btn-outline-danger"
                                            onclick="return confirm('Tem certeza que deseja excluir este produto?')">
                                            <i class="bi bi-trash"></i>
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="7" class="text-center">Nenhum produto encontrado.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>

                </table>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>