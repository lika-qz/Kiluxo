<?php
session_start();
if (!isset($_SESSION['usuario_logado'])) {
    header('Location: index.php');
    exit;
}

require_once "../vendor/php/conexao.php";

// Buscar usuários
try {
    $stmt = $pdo->query("SELECT id, nome, email, criado_em FROM usuarios ORDER BY criado_em DESC");
    $usuarios = $stmt->fetchAll();
} catch (PDOException $e) {
    die("Erro ao buscar usuários: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Usuários | Kiluxo</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS + Ícones -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
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
            <li class="nav-item"><a class="nav-link" href="dashboard.php"><i class="bi bi-speedometer2"></i><span>Dashboard</span></a></li>
            <li class="nav-item"><a class="nav-link" href="vendas.php"><i class="bi bi-cart4"></i><span>Vendas</span></a></li>
            <li class="nav-item"><a class="nav-link" href="produtos.php"><i class="bi bi-box-seam"></i><span>Produtos</span></a></li>
            <li class="nav-item"><a class="nav-link active" href="usuarios.php"><i class="bi bi-people"></i><span>Usuários</span></a></li>
            <div class="divider"></div>
            <li class="nav-item"><a class="nav-link" href="../vendor/php/logout.php"><i class="bi bi-box-arrow-right"></i><span>Sair</span></a></li>
        </ul>
    </div>

    <!-- Conteúdo Principal -->
    <div class="main-content mt-5">
        <div class="container">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h3>Usuários do Sistema</h3>
                <a href="cadastro.php" class="btn btn-success"><i class="bi bi-person-plus-fill"></i> Novo Usuário</a>
            </div>

            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead>
                        <tr>
                            <th>#ID</th>
                            <th>Nome</th>
                            <th>Email</th>
                            <th>Data de Cadastro</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (count($usuarios) > 0): ?>
                            <?php foreach ($usuarios as $user): ?>
                                <tr>
                                    <td><?= htmlspecialchars($user['id']) ?></td>
                                    <td><?= htmlspecialchars($user['nome']) ?></td>
                                    <td><?= htmlspecialchars($user['email']) ?></td>
                                    <td><?= date('d/m/Y H:i', strtotime($user['criado_em'])) ?></td>
                                    <td>
                                       
                                        <a href="../vendor/php/excluirUsuario.php?id=<?= $user['id'] ?>" 
                                           class="btn btn-sm btn-outline-danger"
                                           onclick="return confirm('Deseja realmente excluir este usuário?')">
                                            <i class="bi bi-trash"></i>
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr><td colspan="5" class="text-center">Nenhum usuário encontrado.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
