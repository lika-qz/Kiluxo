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
    <title>Cadastrar Produto | Kiluxo</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.3/font/bootstrap-icons.css">
    <link rel="stylesheet" href="../css/adm.css">
</head>

<body>
    <!-- Navbar -->
    <nav class="navbar navbar-main navbar-expand-lg fixed-top">
        <div class="container-fluid">
            <a class="navbar-brand" href="#"><img src="../images/logo.png" class="navbar-logo"></a>
            <div class="d-flex align-items-center">
                <?php
                $nomeCompleto = $_SESSION['usuario_logado']['nome'];
                $partes = explode(' ', trim($nomeCompleto));
                $primeiro = $partes[0];
                $ultimo = end($partes);
                $nomeExibido = $primeiro . ' ' . $ultimo;
                ?>
                <div class="user-info me-3"><?= htmlspecialchars($nomeExibido) ?></div>
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
            <li class="nav-item"><a class="nav-link" href="#"><i class="bi bi-gear"></i><span>Configurações</span></a>
            </li>
            <div class="divider"></div>
            <li class="nav-item"><a class="nav-link" href="../vendor/php/logout.php"><i
                        class="bi bi-box-arrow-right"></i><span>Sair</span></a></li>
        </ul>
    </div>

    <!-- Main Content -->
    <div class="main-content mt-5">
        <div class="container">
            <h3 class="mb-4">Cadastrar Novo Produto</h3>

            <form method="post" action="../vendor/php/processaProduto.php" enctype="multipart/form-data"
                class="row g-3">
                <div class="col-md-6">
                    <label for="nome" class="form-label">Nome do Produto</label>
                    <input type="text" name="nome" class="form-control" required>
                </div>

                <div class="col-md-6">
                    <label for="categoria" class="form-label">Categoria</label>
                    <select name="categoria" class="form-select" required>
                        <option value="" disabled selected>Selecione a categoria</option>
                        <option value="roupas">Roupas</option>
                        <option value="acessorios">Acessórios</option>
                        <option value="calçados">Calçados</option>
                        <option value="infantil">Infantil</option>
                        <option value="outros">Outros</option>
                    </select>
                </div>

                <div class="col-md-6">
                    <label for="cor" class="form-label">Cor</label>
                    <select name="cor" class="form-select" required>
                        <option value="" disabled selected>Selecione a cor</option>
                        <option value="Preto">Preto</option>
                        <option value="Branco">Branco</option>
                        <option value="Vermelho">Vermelho</option>
                        <option value="Azul">Azul</option>
                        <option value="Verde">Verde</option>
                        <option value="Amarelo">Amarelo</option>
                        <option value="Bege">Bege</option>
                        <option value="Rosa">Rosa</option>
                        <option value="Cinza">Cinza</option>
                    </select>
                </div>

                <div class="col-md-6">
                    <label for="preco" class="form-label">Preço (R$)</label>
                    <input type="number" name="preco" class="form-control" step="0.01" required>
                </div>

                <div class="col-md-6">
                    <label for="estoque" class="form-label">Estoque</label>
                    <input type="number" name="estoque" class="form-control" required>
                </div>

                <div class="col-md-6">
                    <label for="imagem" class="form-label">Imagem do Produto</label>
                    <input type="file" name="imagem" class="form-control" accept="image/*">
                </div>

                <div class="col-md-12">
                    <label for="descricao" class="form-label">Descrição</label>
                    <textarea name="descricao" rows="4" class="form-control"
                        placeholder="Descreva detalhes do produto..." required></textarea>
                </div>

                <div class="col-12">
                    <button type="submit" class="btn btn-success w-100">Cadastrar Produto</button>
                </div>
            </form>

        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>